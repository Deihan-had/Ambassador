<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json; charset=utf-8');

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* ---------------------------------------------------------
 * Admin-configurable settings (would live in an admin panel / config table)
 * --------------------------------------------------------- */
const POINT_RATE          = 500000; // Rp per point
const POINTS_TO_FULL_CARD = 10;
const CARD_CATEGORY_FILTER = 'fashion'; // set to null to accept all categories

$REWARD_CONFIG = [
    'type'        => 'discount', // discount | physical | credit
    'label'       => 'Diskon 20% + Free Ongkir',
    'description' => 'Nikmati potongan 20% dan gratis ongkir untuk transaksi berikutnya.',
    // used only when type === 'discount'
    'discount_code_prefix'  => 'REWARD',
    'discount_valid_days'   => 30,
    // used only when type === 'credit'
    'credit_amount'         => 100000,
];

/* ---------------------------------------------------------
 * Auth guard — same session convention as index.php
 * --------------------------------------------------------- */
function current_user_id()
{
    if (isset($_SESSION['id_users'])) {
        return $_SESSION['id_users'];
    }
    if (isset($_SESSION['user']) && is_array($_SESSION['user'])) {
        if (!empty($_SESSION['user']['id_users'])) return $_SESSION['user']['id_users'];
        if (!empty($_SESSION['user']['id']))        return $_SESSION['user']['id'];
    }
    return null;
}

$user_id = current_user_id();

if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Anda harus login untuk mengakses Rewards.']);
    exit;
}

/* ---------------------------------------------------------
 * Storage layer (JSON file standing in for `user_rewards` table)
 * --------------------------------------------------------- */
class Store
{
    private static string $path = __DIR__ . '/../data/rewards_store.json';

    public static function load(): array
    {
        if (!file_exists(self::$path)) {
            return [];
        }
        $raw = file_get_contents(self::$path);
        $data = json_decode($raw, true);
        return is_array($data) ? $data : [];
    }

    public static function save(array $data): void
    {
        $dir = dirname(self::$path);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        // simple lock to avoid concurrent-write corruption
        $fp = fopen(self::$path, 'c+');
        if ($fp) {
            flock($fp, LOCK_EX);
            ftruncate($fp, 0);
            fwrite($fp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
        }
    }

    public static function getUserCard(string $userId): array
    {
        $all = self::load();
        if (!isset($all[$userId])) {
            $all[$userId] = self::defaultCard();
            self::save($all);
        }
        return $all[$userId];
    }

    public static function saveUserCard(string $userId, array $card): void
    {
        $all = self::load();
        $all[$userId] = $card;
        self::save($all);
    }

    public static function defaultCard(): array
    {
        return [
            'user_id'               => null,
            'current_points'        => 0,
            'total_lifetime_points' => 0,
            'card_status'           => 'active', // active | full | claimed
            'reward_type'           => null,
            'reward_code'           => null,
            'claimed_at'            => null,
            'reset_count'           => 0,
            'points_history'        => [],  // [{date, order_no, amount, points, status}]
            'rewards_history'       => [],  // [{full_date, reward, claimed_at, status}]
        ];
    }
}

/* ---------------------------------------------------------
 * Helpers
 * --------------------------------------------------------- */
function respond(array $payload, int $code = 200): void
{
    http_response_code($code);
    echo json_encode($payload, JSON_UNESCAPED_UNICODE);
    exit;
}

function card_view(array $card): array
{
    // Shape returned to the frontend (adds a couple of derived/display fields)
    global $REWARD_CONFIG;

    $rewardMeta = $card['reward_type']
        ? reward_meta_for($card['reward_type'])
        : reward_meta_for($REWARD_CONFIG['type']);

    return [
        'user_id'               => $card['user_id'],
        'current_points'        => $card['current_points'],
        'total_lifetime_points' => $card['total_lifetime_points'],
        'card_status'           => $card['card_status'],
        'points_to_full'        => POINTS_TO_FULL_CARD,
        'amount_collected'      => $card['current_points'] * POINT_RATE,
        'amount_target'         => POINTS_TO_FULL_CARD * POINT_RATE,
        'progress_percent'      => (int) round(($card['current_points'] / POINTS_TO_FULL_CARD) * 100),
        'reward_type'           => $card['reward_type'] ?? $REWARD_CONFIG['type'],
        'reward_label'          => $rewardMeta['label'],
        'reward_description'    => $rewardMeta['description'],
        'reward_code'           => $card['reward_code'],
        'claimed_at'            => $card['claimed_at'],
        'reset_count'           => $card['reset_count'],
    ];
}

function reward_meta_for(string $type): array
{
    global $REWARD_CONFIG;
    switch ($type) {
        case 'physical':
            return ['label' => $REWARD_CONFIG['label'], 'description' => $REWARD_CONFIG['description']];
        case 'credit':
            return [
                'label' => 'Potongan Rp' . number_format($REWARD_CONFIG['credit_amount'], 0, ',', '.'),
                'description' => 'Potongan langsung otomatis muncul sebagai opsi pembayaran saat checkout.',
            ];
        case 'discount':
        default:
            return ['label' => $REWARD_CONFIG['label'], 'description' => $REWARD_CONFIG['description']];
    }
}

function generate_discount_code(): string
{
    global $REWARD_CONFIG;
    return $REWARD_CONFIG['discount_code_prefix'] . strtoupper(substr(bin2hex(random_bytes(4)), 0, 6));
}

/* ---------------------------------------------------------
 * Routing
 * --------------------------------------------------------- */
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? ($_POST['action'] ?? null);

$card = Store::getUserCard((string) $user_id);
$card['user_id'] = $user_id;

if ($method === 'GET' && $action === 'status') {
    respond(['success' => true, 'data' => card_view($card)]);
}

if ($method === 'GET' && $action === 'history') {
    respond([
        'success' => true,
        'data' => [
            'points_history'  => array_reverse($card['points_history']),
            'rewards_history' => array_reverse($card['rewards_history']),
        ],
    ]);
}

function do_calculate(array $card, string $userId, float $orderTotal, string $orderCategory, string $orderNo): array
{
    if (CARD_CATEGORY_FILTER !== null && $orderCategory !== CARD_CATEGORY_FILTER) {
        return ['success' => true, 'data' => card_view($card), 'points_earned' => 0, 'message' => 'Kategori order tidak memenuhi syarat poin.'];
    }

    if ($orderTotal < POINT_RATE) {
        return ['success' => true, 'data' => card_view($card), 'points_earned' => 0, 'message' => 'Belanja di bawah Rp500.000 tidak mendapat poin.'];
    }

    if ($card['card_status'] === 'full') {
        // Card is already full and waiting to be claimed — spec doesn't carry
        // points into a not-yet-started new card, so we hold the earn until claim.
        return ['success' => true, 'data' => card_view($card), 'points_earned' => 0, 'message' => 'Card sudah penuh, klaim reward Anda dahulu sebelum poin baru dapat dikumpulkan.'];
    }

    $pointsEarned = (int) floor($orderTotal / POINT_RATE);
    $remainingSlots = POINTS_TO_FULL_CARD - $card['current_points'];
    $pointsApplied = min($pointsEarned, $remainingSlots); // extra points beyond 10/10 are forfeited per spec

    $card['current_points']        += $pointsApplied;
    $card['total_lifetime_points'] += $pointsApplied;

    $card['points_history'][] = [
        'date'    => date('Y-m-d H:i:s'),
        'order_no' => $orderNo,
        'amount'  => $orderTotal,
        'points'  => $pointsApplied,
        'status'  => 'Berhasil',
    ];

    if ($card['current_points'] >= POINTS_TO_FULL_CARD) {
        $card['current_points'] = POINTS_TO_FULL_CARD;
        $card['card_status'] = 'full';
    }

    Store::saveUserCard($userId, $card);

    return [
        'success' => true,
        'data' => card_view($card),
        'points_earned' => $pointsApplied,
        'points_forfeited' => max(0, $pointsEarned - $pointsApplied),
    ];
}

if ($method === 'POST' && $action === 'calculate') {
    // Real integration point: call this from ON payment_success in the order flow.
    $orderTotal    = (float) ($_POST['order_total'] ?? 0);
    $orderCategory = $_POST['order_category'] ?? CARD_CATEGORY_FILTER;
    $orderNo       = $_POST['order_no'] ?? ('ORD-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6)));

    respond(do_calculate($card, (string) $user_id, $orderTotal, $orderCategory, $orderNo));
}

if ($method === 'POST' && $action === 'claim') {
    if ($card['card_status'] !== 'full') {
        respond(['success' => false, 'message' => 'Card belum penuh (10/10), reward belum bisa diklaim.'], 400);
    }

    global $REWARD_CONFIG;
    $rewardType = $REWARD_CONFIG['type'];
    $rewardCode = null;

    if ($rewardType === 'discount') {
        $rewardCode = generate_discount_code();
    } elseif ($rewardType === 'physical') {
        $address = trim($_POST['shipping_address'] ?? '');
        if ($address === '') {
            respond(['success' => false, 'message' => 'Alamat pengiriman wajib diisi untuk hadiah fisik.'], 400);
        }
    }
    // 'credit' type needs no extra input — applied automatically at checkout.

    $meta = reward_meta_for($rewardType);
    $claimedAt = date('Y-m-d H:i:s');

    $card['rewards_history'][] = [
        'full_date'  => end($card['points_history'])['date'] ?? $claimedAt,
        'reward'     => $meta['label'],
        'claimed_at' => $claimedAt,
        'status'     => $rewardType === 'physical' ? 'Diproses' : 'Selesai',
    ];

    $card['reward_type'] = $rewardType;
    $card['reward_code']  = $rewardCode;
    $card['claimed_at']   = $claimedAt;
    $card['card_status']  = 'claimed';
    $card['reset_count'] += 1;

    // Reset card to 0/10 for the next cycle, per spec section 4.
    $card['current_points'] = 0;
    $card['card_status']    = 'active';

    Store::saveUserCard((string) $user_id, $card);

    respond([
        'success' => true,
        'message' => 'Reward berhasil diklaim!',
        'reward_type' => $rewardType,
        'reward_code' => $rewardCode,
        'data' => card_view($card),
    ]);
}

if ($method === 'POST' && $action === 'simulate_purchase') {
    // Demo-only helper (no payment gateway wired up in this codebase yet):
    // lets the Rewards page "Simulasikan Pembelian" button exercise the same
    // calculate() path that a real ON payment_success hook would call.
    $amount   = (float) ($_POST['amount'] ?? 500000);
    $orderNo  = 'ORD-' . strtoupper(substr(bin2hex(random_bytes(3)), 0, 6));
    respond(do_calculate($card, (string) $user_id, $amount, (string) CARD_CATEGORY_FILTER, $orderNo));
}

respond(['success' => false, 'message' => 'Unknown action.'], 404);
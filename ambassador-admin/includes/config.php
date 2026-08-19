<?php
if (session_status() !== PHP_SESSION_ACTIVE)
    session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'ambas_sador';
try {
    $conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
    $conn->set_charset('utf8mb4');
} catch (Throwable $e) {
    http_response_code(500);
    die('<h2>Koneksi database gagal</h2><p>' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8') . '</p>');
}
function e($v): string
{
    return htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
}
function rupiah($v): string
{
    return 'Rp ' . number_format((float) $v, 0, ',', '.');
}
function rows(mysqli $db, string $sql, string $types = '', array $params = []): array
{
    $st = $db->prepare($sql);
    if ($types !== '')
        $st->bind_param($types, ...$params);
    $st->execute();
    $r = $st->get_result();
    $a = $r->fetch_all(MYSQLI_ASSOC);
    $st->close();
    return $a;
}
function row(mysqli $db, string $sql, string $types = '', array $params = []): ?array
{
    $a = rows($db, $sql, $types, $params);
    return $a[0] ?? null;
}
function scalar(mysqli $db, string $sql, string $types = '', array $params = []): mixed
{
    $r = row($db, $sql, $types, $params);
    return $r ? array_values($r)[0] : null;
}
function execute_stmt(mysqli $db, string $sql, string $types = '', array $params = []): int
{
    $st = $db->prepare($sql);
    if ($types !== '')
        $st->bind_param($types, ...$params);
    $st->execute();
    $id = $st->insert_id;
    $st->close();
    return $id;
}
function admin_required(): void
{
    if (empty($_SESSION['is_admin'])) {
        header('Location: admin.php');
        exit;
    }
}
function flash(string $type, string $msg): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $msg];
}
function pull_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}
function has_table(mysqli $db, string $table): bool
{
    return (int) scalar($db, "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema=DATABASE() AND table_name=?", 's', [$table]) > 0;
}
function has_column(mysqli $db, string $table, string $column): bool
{
    return (int) scalar($db, "SELECT COUNT(*) FROM information_schema.columns WHERE table_schema=DATABASE() AND table_name=? AND column_name=?", 'ss', [$table, $column]) > 0;
}
function status_class(string $s): string
{
    $s = strtolower($s);
    if (str_contains($s, 'selesai') || str_contains($s, 'aktif') || str_contains($s, 'paid'))
        return 'ok';
    if (str_contains($s, 'batal') || str_contains($s, 'gagal') || str_contains($s, 'habis'))
        return 'bad';
    return 'warn';
}
function order_status_label(string $s): string
{
    return match (strtolower(trim($s))) { 'pending', 'baru' => 'Menunggu', 'diproses', 'processing' => 'Diproses', 'dikirim', 'shipping', 'shipped' => 'Dikirim', 'selesai', 'completed', 'done' => 'Selesai', 'dibatalkan', 'cancelled', 'canceled' => 'Dibatalkan', default => ucfirst($s)};
}
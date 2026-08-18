<?php

require_once __DIR__ . '/../../config/database.php';

class User
{
    var $conn;
    var $table = "users";


    function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }


    /*
    |--------------------------------------------------------------------------
    | REGISTER
    |--------------------------------------------------------------------------
    */

    function register($idUsers, $username, $email, $password)
    {
        // Cek username
        $cekUsername = $this->findByUsername($username);

        if ($cekUsername) {
            return false;
        }

        // Cek email
        $cekEmail = $this->findByEmail($email);

        if ($cekEmail) {
            return false;
        }

        // Password di-hash
        $hashedPassword =
            password_hash(
                $password,
                PASSWORD_BCRYPT
            );


        $role = "user";

        $query =
            "INSERT INTO " . $this->table . "
            (
                id_users,
                username,
                email,
                password,
                role
            )
            VALUES (?, ?, ?, ?, ?)";

        $stmt =
            mysqli_prepare(
                $this->conn,
                $query
            );


        if (!$stmt) {
            return false;
        }

        mysqli_stmt_bind_param(
            $stmt,
            "sssss",
            $idUsers,
            $username,
            $email,
            $hashedPassword,
            $role
        );


        $hasil =
            mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


        return $hasil;
    }


    /*
    |--------------------------------------------------------------------------
    | CARI USER BERDASARKAN USERNAME
    |--------------------------------------------------------------------------
    */

    function findByUsername($username)
    {
        $query =
            "SELECT *
             FROM " . $this->table . "
             WHERE username = ?
             LIMIT 1";


        $stmt =
            mysqli_prepare(
                $this->conn,
                $query
            );


        if (!$stmt) {
            return false;
        }


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $username
        );


        mysqli_stmt_execute($stmt);


        $result =
            mysqli_stmt_get_result(
                $stmt
            );


        $user =
            mysqli_fetch_assoc(
                $result
            );


        mysqli_stmt_close($stmt);


        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | CARI USER BERDASARKAN EMAIL
    |--------------------------------------------------------------------------
    */

    function findByEmail($email)
    {
        $query =
            "SELECT *
             FROM " . $this->table . "
             WHERE email = ?
             LIMIT 1";


        $stmt =
            mysqli_prepare(
                $this->conn,
                $query
            );


        if (!$stmt) {
            return false;
        }


        mysqli_stmt_bind_param(
            $stmt,
            "s",
            $email
        );


        mysqli_stmt_execute($stmt);


        $result =
            mysqli_stmt_get_result(
                $stmt
            );


        $user =
            mysqli_fetch_assoc(
                $result
            );


        mysqli_stmt_close($stmt);


        return $user;
    }


    /*
    |--------------------------------------------------------------------------
    | GOOGLE LOGIN
    |--------------------------------------------------------------------------
    */

    function findOrCreateGoogleUser(
        $googleId,
        $name,
        $email
    ) {

        /*
        |----------------------------------------------------------------------
        | CARI BERDASARKAN GOOGLE ID
        |----------------------------------------------------------------------
        */

        $query =
            "SELECT *
             FROM " . $this->table . "
             WHERE google_id = ?
             LIMIT 1";


        $stmt =
            mysqli_prepare(
                $this->conn,
                $query
            );


        if ($stmt) {

            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $googleId
            );


            mysqli_stmt_execute($stmt);


            $result =
                mysqli_stmt_get_result(
                    $stmt
                );


            $user =
                mysqli_fetch_assoc(
                    $result
                );


            mysqli_stmt_close($stmt);


            if ($user) {
                return $user;
            }
        }


        /*
        |----------------------------------------------------------------------
        | CARI BERDASARKAN EMAIL
        |----------------------------------------------------------------------
        */

        $user =
            $this->findByEmail($email);

        if ($user) {

            /*
            |------------------------------------------------------------------
            | HUBUNGKAN GOOGLE ID KE USER LAMA
            |------------------------------------------------------------------
            */

            $query =
                "UPDATE " . $this->table . "
                 SET google_id = ?
                 WHERE id_users = ?";


            $stmt =
                mysqli_prepare(
                    $this->conn,
                    $query
                );


            if ($stmt) {

                mysqli_stmt_bind_param(
                    $stmt,
                    "ss",
                    $googleId,
                    $user['id_users']
                );

                mysqli_stmt_execute($stmt);

                mysqli_stmt_close($stmt);
            }


            $user['google_id'] =
                $googleId;


            return $user;
        }


        /*
        |----------------------------------------------------------------------
        | BUAT USER GOOGLE BARU
        |----------------------------------------------------------------------
        */

        $namaBersih =
            strtolower(
                preg_replace(
                    '/[^a-zA-Z0-9]/',
                    '',
                    $name
                )
            );


        if ($namaBersih == "") {

            $pecahEmail =
                explode(
                    '@',
                    $email
                );


            $namaBersih =
                $pecahEmail[0];
        }


        $username =
            $namaBersih .
            '_' .
            substr(
                $googleId,
                -4
            );


        /*
        |----------------------------------------------------------------------
        | PASTIKAN USERNAME TIDAK SAMA
        |----------------------------------------------------------------------
        */

        $usernameAwal =
            $username;

        $angka = 1;


        while (
            $this->findByUsername($username)
        ) {

            $username =
                $usernameAwal .
                $angka;

            $angka++;
        }


        /*
        |----------------------------------------------------------------------
        | DATA USER BARU
        |----------------------------------------------------------------------
        */

        $idUsers =
            'USR-GGL-' .
            time();


        $passwordAsal =
            password_hash(
                bin2hex(
                    random_bytes(10)
                ),
                PASSWORD_BCRYPT
            );


        $role = "user";


        /*
        |----------------------------------------------------------------------
        | INSERT USER GOOGLE
        |----------------------------------------------------------------------
        */

        $query =
            "INSERT INTO " . $this->table . "
            (
                id_users,
                username,
                email,
                google_id,
                password,
                role
            )
            VALUES (?, ?, ?, ?, ?, ?)";


        $stmt =
            mysqli_prepare(
                $this->conn,
                $query
            );


        if (!$stmt) {
            return false;
        }


        mysqli_stmt_bind_param(
            $stmt,
            "ssssss",
            $idUsers,
            $username,
            $email,
            $googleId,
            $passwordAsal,
            $role
        );


        $hasil =
            mysqli_stmt_execute($stmt);


        mysqli_stmt_close($stmt);


        if (!$hasil) {
            return false;
        }


        /*
        |----------------------------------------------------------------------
        | AMBIL USER YANG BARU DIBUAT
        |----------------------------------------------------------------------
        */

        return $this->findByEmail($email);
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h3 class="text-center mt-5">Gửi email thông báo ngày giỗ</h3>
        
        <?php
        require_once "amlich.php";
        require_once "send-email.php";
        require_once "config.php";
        require_once "weekday.php";

        $today_lunar = alhn();
        // $today_lunar = '03-07-2021';
        $today_lunar_day = explode("-", $today_lunar)[0];
        $today_lunar_month = explode("-", $today_lunar)[1];
        $today_lunar_year = explode("-", $today_lunar)[2];

        date_default_timezone_set("Asia/Ho_Chi_Minh"); 
        $solarDay = date("d");
        $solarMonth = date("m");
        $solarYear = date("Y");

        echo "<h5>Âm lịch hôm nay là: $weekday, ngày $today_lunar</h5>";

        $data = "SELECT *, from_unixtime(deathdate, '%d-%m-%Y') AS death_date
        FROM
        (SELECT ftree_v1_4_users.id AS userID, ftree_v1_4_members.id AS memberID, ftree_v1_4_members.firstname, ftree_v1_4_members.lastname, ftree_v1_4_users.email, ftree_v1_4_members.deathdate, ftree_v1_4_members.photo, ftree_v1_4_members.gender
        FROM ftree_v1_4_users
        JOIN ftree_v1_4_members ON ftree_v1_4_users.id = ftree_v1_4_members.author) AS demo
        ORDER BY userID;";

        // lọc member sắp tới ngày giỗ
        if ($members = mysqli_query($conn, $data)) {
            echo '<h5>Members sắp đến ngày giỗ: </h5>';
            $count = 0;
            while ($member = mysqli_fetch_array($members)) {
                $death_date = $member['death_date'];
                $death_date_compare = substr($death_date, 0, 5);
                $firstname = $member['firstname'];
                $lastname = $member['lastname'];
                $photo = $member['photo'];

                for ($i = 0; $i <= 7; $i++) {
                    $send_date = strtotime("+$i day", strtotime($today_lunar));
                    $send_date = date('d-m', $send_date);
                    if ($death_date_compare == $send_date) {
                        $count++;
                        if ($i == 0) {
                            echo "- Hôm nay ngày giỗ của Thành viên <b> $firstname $lastname </b>, ngày $death_date âm lịch.<br/>
                            ";
                        } else {
                            echo "- Còn $i ngày nữa là đến ngày giỗ của Thành viên <b> $firstname $lastname </b>, ngày $death_date âm lịch.<br/>";
                        }
                    }
                }
            }
            if ($count == 0) {
                echo "- Không có member nào có ngày giỗ trong 7 ngày tới";
            } else {
                echo '
                <form class="mt-4" action="" method="post">
                <button class="btn btn-primary" type="submit" name="sendmail">Send Mail</button>
                </form>
                ';
            }
        }
        echo "<br>";

        // sendmail
        if (isset($_POST['sendmail'])) {
            if ($members = mysqli_query($conn, $data)) {
                while ($member = mysqli_fetch_array($members)) {
                    $death_date = $member['death_date'];
                    $death_date_compare = substr($death_date, 0, 5);
                    $firstname = $member['firstname'];
                    $lastname = $member['lastname'];
                    $photo = $member['photo'];
                    $gender = $member['gender'];
                    if ($photo == '') {
                        switch ($gender) {
                            case '0':
                                $link_photo = 'https://labtoidayhoc.s3.ap-southeast-1.amazonaws.com/giapnguyen/5049207_avatar_people_person_profile_user_icon.png';
                                break;
                            case '1':
                                $link_photo = 'https://labtoidayhoc.s3.ap-southeast-1.amazonaws.com/giapnguyen/628297_avatar_grandmother_mature_old_person_icon.png';
                                break;
                            case '2':
                                $link_photo = 'https://labtoidayhoc.s3.ap-southeast-1.amazonaws.com/giapnguyen/628283_avatar_grandfather_male_man_mature_icon.png';
                                break;
                        }
                    } else {
                        $link_photo = "https://demo.ongbata.vn/$photo";
                    }
                    $userMail = $member['email'];
                    $userMail = 'ngocgiap162@gmail.com';
                    $mailSubject = 'Ongbata thông báo ngày giỗ';

                    for ($i = 0; $i <= 7; $i++) {
                        $send_date = strtotime("+$i day", strtotime($today_lunar));
                        $send_date = date('d-m', $send_date);
                        if ($death_date_compare == $send_date) {
                            if ($i == 0) {
                                $mailNotify = "<h2>Kính báo anh/chị,</h2>
                                <p>Hôm nay là ngày giỗ của thành viên <b></p>
                                <p><b> $firstname $lastname.</b></p>
                                <p>$weekday, ngày $death_date_compare-$today_lunar_year âm lịch.</p>";
                            } else {
                                $mailNotify = "<h2>Kính báo anh/chị,</h2>
                                <p>Còn $i ngày nữa là đến ngày giỗ của thành viên</p>
                                <p><b> $firstname $lastname.</b></p>
                                <p>$weekday, ngày $death_date_compare-$today_lunar_year âm lịch.</p>";
                            }
                            $mailBody = "
                                <!DOCTYPE html>
                                <html lang='en'>
                                <head>
                                    <meta charset='UTF-8'>
                                    <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                                    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                                    <title>Send mail page</title>
                                    <style>
                                        * {
                                            box-sizing: border-box;
                                        }
                                        p {
                                        margin: 8px 0;
                                        }
                                        img {
                                            width: 100%;
                                        }
                                        .text-center {
                                            text-align: center;
                                        }
                                        .align-center {
                                            align-items: center;
                                        }
                                        .fs-18 {
                                            font-size: 18px;
                                        }
                                        .fs-22 {
                                            font-size: 22px;
                                        }
                                        .fs-36 {
                                            font-size: 36px;
                                        }
                                        .d-flex {
                                            display: flex;
                                        }
                                        .p-top-bt-12 {
                                            padding: 12px 0px;
                                        }
                                        .p-top-bt-8 {
                                            padding: 8px 0px;
                                        }
                                        .w-100 {
                                            width: 100%;
                                        }
                                        .w-50 {
                                            width: 50%;
                                        }
                                        .border-bt {
                                            border-bottom: 1px solid white;
                                        }
                                        .border-right {
                                            border-right: 1px solid white;
                                        }
                                        .container {
                                            width: 80%;
                                            margin: auto;
                                            border: 1px solid #ccc;
                                        }
                                        .header {
                                            padding: 12px;
                                        }
                                        .footer,
                                        .header {
                                            background-color: #0B730B;
                                            color: #fff;
                                        }
                                        .body {
                                            display: flex;
                                            border-top: 1px solid white;
                                            background-color: #7CE6E6;
                                            padding: 12px 48px;
                                        }
                                        .body_left {
                                            width: 70%;
                                            align-items: center;
                                            padding-right: 48px;
                                            padding-top: 24px;
                                        }
                                        .body_right {
                                            width: 30%;
                                        }
                                        .footer-lunar {
                                            border-right: 1px solid white;
                                        }
                                        .member_img img {
                                            border-radius: 50%;
                                        }
                                        .big-day {
                                            font-size: 75px;
                                        }
                                    </style>
                                </head>
                                <body>
                                    <div class='container'>
                                        <div class='header fs-36 text-center'>
                                            Thông báo ngày giỗ
                                        </div>
                                        <div class='body text-center border-bt'>
                                            <div class='body_left fs-22'>$mailNotify</div>
                                            <div class='body_right'>
                                                <div class='member_img'>
                                                    <img src='$link_photo' alt=''>
                                                </div>
                                                <div class='fs-22'><b>$death_date</b></div>
                                            </div>
                                        </div>
                                        <div class='footer d-flex w-100'>
                                            <div class='footer-lunar text-center w-50'>
                                                <div class='footer-top fs-22 p-top-bt-12 border-bt'>Âm lịch hôm nay</div>
                                                <div class='footer-bottom p-top-bt-12 fs-18 align-center'>
                                                    <div class='big-day'>$today_lunar_day</div>
                                                    <div>Tháng $today_lunar_month - $today_lunar_year</div>
                                                </div>
                                            </div>
                                            <div class='footer-solar text-center w-50'>
                                                <div class='footer-top fs-22 p-top-bt-12 border-bt'>Dương lịch hôm nay</div>
                                                <div class='footer-bottom p-top-bt-12 fs-18 align-center'>
                                                    <div class='big-day'>$solarDay</div>
                                                    <div>Tháng $solarMonth - $solarYear</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </body>
                                </html>
                                ";
                            echo "- $firstname $lastname: ";
                            sendEmail($userMail, $mailSubject, $mailBody);
                            echo "<br>";
                            sleep(10);
                        }
                    }
                }
            }
        }
        ?>

    </div>

</body>

</html>
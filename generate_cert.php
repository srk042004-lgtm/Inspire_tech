<?php
include 'db_connect.php';
$id = (int)$_GET['id'];
$s = $conn->query("SELECT * FROM students WHERE id=$id")->fetch_assoc();
?>
<!DOCTYPE html>
<html>

<head>
    <link href="https://fonts.googleapis.com/css2?family=Amiri&display=swap" rel="stylesheet">
    <style>
        /* page layout */
        body {
            font-family: 'Georgia', serif;
            display: flex;
            justify-content: center;
            padding: 50px;
            background: #f9f9f9;
        }

        .cert {
            width: 800px;
            max-width: 100%;
            padding: 40px 60px 60px;
            text-align: center;
            border: 8px solid #2c3e50;
            background: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            position: relative;
            line-height: 1.3;
        }

        /* optional watermark if you add an image named watermark.png */
        /* .cert { background: url('uploads/watermark.png') no-repeat center; background-size: 80%; } */

        .logo {
            width: 120px;
            height:120px;
            margin-bottom: 20px;
            border-radius: 50%;
            background: transparent;
            object-fit: cover;
        }

        h1 {
            font-size: 32px;
            color: #2c3e50;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }

        p {
            font-size: 16px;
            color: #333;
            margin: 6px 0;
        }

        .name {
            font-size: 32px;
            font-weight: bold;
            border-bottom: 2px solid #2c3e50;
            display: block;
            padding: 0 20px;
            margin: 20px auto;
            word-break: break-word;
            max-width: 80%;
        }

        .course {
            font-size: 24px;
            font-weight: 600;
            margin: 8px 0;
        }

        .praise {
            font-size: 18px;
            margin-bottom: 15px;
            color: #555;
            font-style: italic;
            font-family: 'Amiri', serif;
        }

        .date {
            position: absolute;
            bottom: 20px;
            left: 60px;
            font-size: 14px;
            color: #555;
        }

        .sig {
            position: absolute;
            bottom: 20px;
            right: 60px;
            text-align: center;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body onload="window.print()">
    <div class="cert">
        <div class="praise">بِسْمِ ٱللَّٰهِ ٱلرَّحْمَـٰنِ ٱلرَّحِيمِ</div>
        <img src="uploads/logo.jpg" class="logo">
        <h1>CERTIFICATE</h1>
        <p>All praise is due to <strong>ALLAH SWT</strong>, the Most Merciful.</p>
        <p style="margin-top:25px;">This is to certify that</p>
        <div class="name"><?= strtoupper($s['name']) ?></div>
        <p>in recognition of exemplary performance and unwavering commitment to learning,</p>
        <div class="course"><?= $s['enrolled_course'] ?></div>
        <p>has successfully completed the above course</p>
        <p>conducted by <strong>Inspire Tech School of IT, Nowshera Cantt.</strong></p>
        <p style="margin-top:20px; font-style:italic;">Your dedication, perseverance and passion for knowledge are truly commendable. May you continue to achieve excellence in every endeavor.</p>
        <p style="margin-top:10px; font-size:14px;">This institution is registered with the Skill Development Council.</p>

        <div class="date">Dated: <?= date('d-M-Y') ?></div>
        <div class="sig">
            Principal<br>
            Raheel Ahmad
        </div>
    </div>
</body>

</html>
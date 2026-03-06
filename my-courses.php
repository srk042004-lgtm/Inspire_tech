<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Computer Courses - Inspire Tech Computer Academy</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

<style>

body{
font-family:'Segoe UI', sans-serif;
background:linear-gradient(120deg,#f0f4ff,#ffffff);
}


/* NAVBAR */
.navbar{
background:rgba(0,0,0,0.7);
backdrop-filter:blur(10px);
}

.nav-link{
color:white !important;
transition:0.3s;
}

.nav-link:hover{
color:#00ffd5 !important;
transform:translateY(-3px);
}


/* HEADER */
.header{
height:50vh;
background:linear-gradient(rgba(0,0,0,0.7),rgba(0,0,0,0.7)), url('https://images.unsplash.com/photo-1519389950473-47ba0277781c');
background-size:cover;
display:flex;
align-items:center;
justify-content:center;
color:white;
text-align:center;
}


/* COURSE CARD */
.course-card{
background:white;
border-radius:20px;
padding:30px;
box-shadow:0 10px 30px rgba(0,0,0,0.1);
transition:0.4s;
position:relative;
overflow:hidden;
}

.course-card:before{
content:"";
position:absolute;
width:100%;
height:100%;
background:linear-gradient(45deg,#007bff,#00ffd5);
top:100%;
left:0;
transition:0.4s;
z-index:0;
}

.course-card:hover:before{
top:0;
}

.course-card:hover{
color:white;
transform:translateY(-15px) scale(1.03);
}

.course-card *{
position:relative;
z-index:1;
}

.course-icon{
font-size:40px;
margin-bottom:15px;
color:#007bff;
transition:0.4s;
}

.course-card:hover .course-icon{
color:white;
transform:scale(1.2);
}


/* BUTTON */
.btn-course{
background:linear-gradient(45deg,#007bff,#00ffd5);
color:white;
border:none;
border-radius:30px;
padding:10px 25px;
transition:0.3s;
}

.btn-course:hover{
transform:scale(1.1);
}

footer{
background:black;
color:white;
}


</style>

</head>
<body>


<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg fixed-top">
<div class="container">

<a class="navbar-brand text-white">Inspire Tech</a>

<ul class="navbar-nav ms-auto">
<li class="nav-item"><a class="nav-link" href="home_page.php">Home</a></li>
<li class="nav-item"><a class="nav-link" href="2_courses.html">Courses</a></li>
<li class="nav-item"><a class="nav-link" href="student-portal.php">Student Portal</a></li>
<li class="nav-item"><a class="nav-link" href="contact.php#contact">Contact</a></li>
</ul>

</div>
</nav>



<!-- HEADER -->
<div class="header">

<div>

<h1>Our Computer Courses</h1>

<p>Professional Diploma and Short Courses</p>

</div>

</div>


<!-- COURSES -->
<section class="container py-5">

<div class="row g-4">


<!-- MS OFFICE -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-file-word course-icon"></i>

<h3>MS Office</h3>

<p>Word, Excel, PowerPoint Complete Training</p>

<a href="2_courses.html#ms-office" class="btn-course">View Details</a>


</div>

</div>


<!-- CIT -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-computer course-icon"></i>

<h3>CIT</h3>

<p>Certificate in Information Technology</p>


<a href="2_courses.html#cit" class="btn-course">View Details</a>

</div>

</div>

<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-computer course-icon"></i>

<h3>PYTHON</h3>

<p>Certificate in Information Technology</p>


<a href="2_courses.html#python" class="btn-course">View Details</a>

</div>

</div>
<!-- DIT -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-laptop-code course-icon"></i>

<h3>DIT</h3>

<p>Diploma in Information Technology</p>


<a href="2_courses.html#dit" class="btn-course">View Details</a>

</div>

</div>


<!-- TYPING -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-keyboard course-icon"></i>

<h3>Typing</h3>

<p>English and Urdu Typing Course</p>


<a href="2_courses.html#typing" class="btn-course">View Details</a>

</div>

</div>


<!-- DIGITAL MARKETING -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-bullhorn course-icon"></i>

<h3>Digital Marketing</h3>

<p>Facebook, YouTube, Freelancing</p>


<a href="2_courses.html#digital-marketing" class="btn-course">View Details</a>

</div>

</div>


<!-- Web_development -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-code course-icon"></i>

<h3>Web_development</h3>

<p>Create Professional Websites</p>


<a href="2_courses.html#web-development" class="btn-course">View Details</a>

</div>

</div>


<!-- AI -->
<div class="col-md-4">

<div class="course-card text-center">

<i class="fa-solid fa-robot course-icon"></i>

<h3>Artificial Intelligence</h3>

<p>Become AI Engineer</p>


<a href="2_courses.html#ai" class="btn-course">View Details</a>

</div>

</div>


</div>

</section>
<footer class="py-4 bg-dark text-white text-center">
  <div class="container">
    <p class="mb-1">For any inquiries please visit <a href="contact.php#contact" class="text-info">the contact page</a>.</p>
    <p class="mb-0">© Inspire Tech Computer Academy</p>
  </div>
</footer>


<!-- WHATSAPP FLOAT -->
<a href="https://wa.me/923462345453" class="whatsapp">
<i class="fab fa-whatsapp"></i>
</a>


</body>
</html>



</body>
</html>

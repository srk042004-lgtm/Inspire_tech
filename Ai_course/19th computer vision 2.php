<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 19 - Advanced Vision Projects | Inspire Tech Academy</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"
    />    <link rel="stylesheet" href="../style.css" /></head>
  <body class="ai-course-page ai-course-page">
    <?php include 'navbar.php'; ?>

    <div class="container">
    <div class="row">
        <div class="col-lg-3 d-none d-lg-block">
            <div class="sidebar">
                <h5>Ai_course Map</h5>
                
                <a href="18th Computer vision 1 .php" class="class-item"><i class="fas fa-lock"></i> 18. Computer Vision of AI 1</a>
                <a href="19th computer vision 2.php" class="class-item active"><i class="fas fa-play-circle"></i> 19. Computer Vision of AI 2</a>
                <a href="20th NLP Natural Language Processing.php" class="class-item"><i class="fas fa-lock"></i> 20. NLP Natural Language Processing of AI</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="proj-badge">
              <i class="fas fa-video me-2"></i> Stream Processing
            </div>
            <h2 class="topic-header">Class 56: Live Video Processing Logic</h2>
            <p>
              Processing live video is significantly different from static
              images because you must manage the "Frames Per Second" (FPS) to
              ensure zero lag. In this class, we use the
              <code>cv2.VideoCapture()</code> object to tap into webcam streams
              or IP cameras. You will learn the "While Loop" logic that
              captures, processes, and displays frames indefinitely until a
              break-key is pressed. We explore techniques like
              <strong>Frame Skipping</strong> and
              <strong>Resolution Scaling</strong> to maintain high performance
              on lower-end hardware. You will also implement real-time text and
              graphics overlays, allowing the AI to "draw" its conclusions
              directly onto the live feed. At Inspire Tech, we emphasize the
              importance of <strong>Multithreading</strong>, where the camera
              capture and the AI processing run on separate CPU cores to prevent
              frame buffering. This is the foundation for any real-world
              surveillance or robotics application.
            </p>

            <div class="code-window">
              cap = cv2.VideoCapture(0)<br />
              while True:<br />
              &nbsp;&nbsp;ret, frame = cap.read()<br />
              &nbsp;&nbsp;# AI Processing Logic Here<br />
              &nbsp;&nbsp;cv2.imshow('Live AI', frame)<br />
              &nbsp;&nbsp;if cv2.waitKey(1) == ord('q'): break
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/oXlwWbU8l2o"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="proj-badge">
              <i class="fas fa-id-card me-2"></i> Identity AI
            </div>
            <h2 class="topic-header">
              Class 57: AI Attendance & Face Recognition
            </h2>
            <p>
              Unlike simple detection (finding a face), **Face Recognition**
              identifies exactly *who* the person is. In this project, we use
              <strong>Facial Embeddings</strong>—a method that converts facial
              features into a 128-dimension vector of numbers. We use the
              <code>face_recognition</code> library, built on top of dlib's
              state-of-the-art Deep Learning models. You will learn how to
              "enroll" new users by calculating their unique facial
              "fingerprint" and storing it in a database. When a person appears
              on camera, the AI compares their current embedding with the
              database using <strong>Euclidean Distance</strong> to find a
              match. We also implement <strong>Liveness Detection</strong> to
              ensure the system cannot be fooled by a photograph. At our
              Nowshera lab, we build a complete Attendance System that logs the
              user's name and time into an Excel or SQL database automatically
              upon recognition.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/sz25MeU_7Ww"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="proj-badge">
              <i class="fas fa-car me-2"></i> Mobile Vision
            </div>
            <h2 class="topic-header">Class 58 & 59: YOLOv8 Object Tracking</h2>
            <p>
              We take object detection to the next level by implementing
              <strong>YOLOv8</strong>, the latest and fastest version of the
              "You Only Look Once" family. This class focuses on
              <strong>Object Tracking</strong>, where the AI assigns a unique ID
              to every detected item (car, person, bag) and follows it as it
              moves across the screen. You will learn how to use
              <strong>Centroid Tracking</strong> and
              <strong>Kalman Filters</strong> to predict where an object will
              move next, even if it is temporarily hidden behind another object.
              We implement "Virtual Tripwires," where the system counts how many
              people enter or exit a specific zone in the frame. This technology
              is critical for traffic management and retail analytics. We also
              touch upon <strong>Transfer Learning</strong>, showing you how to
              train YOLO on your own custom objects—like detecting specific
              industrial tools or agricultural pests relevant to our local
              economy in Pakistan.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/m9fH9OWn8YM"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="proj-badge">
              <i class="fas fa-trophy me-2"></i> Graduation Lab
            </div>
            <h2 class="topic-header">Class 60: The Ultimate Vision Capstone</h2>
            <p>
              For your final Computer Vision project, you will build an
              <strong>AI Gesture-Controlled Interface</strong>. This system uses
              **Mediapipe** to detect 21 different landmark points on a human
              hand in real-time. You will learn to map specific hand gestures
              (like a pinch or a palm-up) to computer commands, such as
              controlling the volume, moving the mouse, or drawing on a digital
              whiteboard. This project combines everything you've learned: frame
              processing, landmark localization, coordinate math, and real-time
              execution. You will have to handle the
              <strong>Coordinate Mapping</strong> logic to translate a small
              webcam window into full-screen monitor coordinates accurately.
              This project is a showstopper for any interview, proving you can
              create human-centric AI that bridges the gap between the physical
              and digital worlds. At Inspire Tech Academy, this marks your
              transition from a student to a Vision Engineer.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/01sAkU_NvOY"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-check-double me-2"></i> Senior Vision Engineer
              Quiz
            </h3>
            <div class="quiz-progress"><div id="progress-bar"></div></div>

            <div id="quiz-content">
              <h5 id="question-text">
                Q1: What do we call the 128 numerical values that represent a
                person's unique facial features?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(true)">
                  Facial Embeddings
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Pixel Gradients
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Color Histograms
                </div>
              </div>
            </div>
            <div id="feedback" class="mt-3 fw-bold"></div>
          </div>

          <div
            id="nav-container"
            class="d-flex justify-content-between mt-5 mb-5 d-none"
          >
            <button
              class="btn btn-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> Basic Vision
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next: Final Evaluation <i class="fas fa-arrow-right ms-2"></i>
            </button>
          </div>
        </div>
      </div>
    </div>

    <footer>
      <div class="container text-center text-md-start">
        <div class="row">
          <div class="col-md-6 mb-4">
            <div class="fw-bold text-info fs-4 mb-2">Inspire Tech Academy</div>
            <p>
              From pixels to perception. You have completed the Computer Vision
              module. You are now ready to build the next generation of visual
              intelligence.
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p>Location: Khattak Building, Nowshera Cantt</p>
            <p>Instructor: Raheel Ahmad | 03462345453</p>
          </div>
        </div>
        <hr class="bg-secondary" />
        <center class="small opacity-50">
          © 2026 Inspire Tech. All Rights Reserved.
        </center>
      </div>
    </footer>

    <script src="/support hub.js">
      let currentQuestion = 1;
      const questions = [
        {
          q: "What do we call the 128 numerical values that represent a person's unique facial features?",
          options: ["Facial Embeddings", "Pixel Gradients", "Color Histograms"],
          correct: 0,
        },
        {
          q: "Which object detection model is famous for its 'One Look' speed and efficiency?",
          options: ["Random Forest", "YOLO", "Naive Bayes"],
          correct: 1,
        },
        {
          q: "What does a 'Kalman Filter' help with in Computer Vision?",
          options: [
            "Changing image brightness",
            "Predicting an object's future position",
            "Deleting background noise",
          ],
          correct: 1,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        const bar = document.getElementById("progress-bar");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__flash'>✔️ Correct! Project data verified.</span>";
          let progress = (currentQuestion / 3) * 100;
          bar.style.width = progress + "%";

          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__tada'>🎓 CV Module Mastered! Redirecting to Professional Portfolio...</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
              setTimeout(() => {
                alert(
                  "Certificate Unlocked: You have completed the Computer Vision Specialization at Inspire Tech.",
                );
              }, 1000);
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect! Recalibrating AI logic...</span>";
          setTimeout(() => {
            currentQuestion = 1;
            bar.style.width = "0%";
            loadQuestion();
            feedback.innerText = "";
          }, 2000);
        }
      }

      function loadQuestion() {
        const qData = questions[currentQuestion - 1];
        document.getElementById("question-text").innerText =
          `Q${currentQuestion}: ${qData.q}`;
        const optionsContainer = document.getElementById("options-container");
        optionsContainer.innerHTML = "";

        qData.options.forEach((opt, index) => {
          const div = document.createElement("div");
          div.className = "quiz-option";
          div.innerText = opt;
          div.onclick = () => checkAnswer(index === qData.correct);
          optionsContainer.appendChild(div);
        });
      }

      loadQuestion();
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>






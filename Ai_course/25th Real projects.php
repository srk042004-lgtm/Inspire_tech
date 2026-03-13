<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>
      AI Class 25 - Real-World Implementation | Inspire Tech Academy
    </title>

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
                <a href="24th Final Capstone module.php" class="class-item"><i class="fas fa-lock"></i> 24. Final Capstone Module in AI</a>
                <a href="25th Real projects.php" class="class-item active"><i class="fas fa-play-circle"></i> 25. Real Projects of AI</a>
                <a href="26th Real projects.php" class="class-item"><i class="fas fa-lock"></i> 26. Real Projects of AI 2</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeInUp">
            <h2 class="topic-header">Class 81: Enterprise-Grade AI Chatbot</h2>
            <p>
              This project moves beyond simple "Q&A" to a **Context-Aware
              Dialogue System**. Students will learn how to build a
              multi-layered chatbot using <strong>FastAPI</strong> and
              <strong>LangChain</strong>. We explain the "Memory State"
              architecture, which allows the bot to store user details in a SQL
              database and refer back to them mid-conversation. You will
              implement <strong>Semantic Routing</strong>, where the AI first
              detects the user's intent (e.g., "Billing" vs "Tech Support") and
              routes the query to a specialized prompt template. We focus on
              integrating <strong>Webhooks</strong> to allow the chatbot to
              perform real actions, such as checking a product's stock or
              booking a flight. For ease of use, we provide a pre-built React
              template for the UI, focusing the student's energy on the backend
              AI logic and API security.
            </p>

            <div class="practical-box">
              <h6>Practical Example: E-Commerce Bot</h6>
              "Build a bot for a Nowshera-based store that can check order
              status via an API and recommend products based on the user's past
              purchase history stored in a PostgreSQL database."
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/aircAruvnKk"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">Class 82: Biometric Attendance System</h2>
            <p>
              In this project, we develop a professional **Face Recognition
              Attendance System** that functions in varying lighting conditions.
              We move away from basic libraries to using
              <strong>Dlib's 128D Facial Embeddings</strong>, which are highly
              accurate for identifying individuals. Students will learn the
              "Registration Pipeline": capturing a user's image, calculating the
              embedding vector, and storing it in a
              <strong>Vector Database</strong> (like Pinecone or Milvus). We
              cover <strong>Anti-Spoofing</strong> techniques to prevent the
              system from being fooled by mobile phone photos of a person. You
              will also learn how to link the recognition event to an automated
              email notification system using Python's <code>smtplib</code>. At
              Inspire Tech, we guide you through the process of building a
              dashboard that displays real-time 'Check-in' logs for
              administrators.
            </p>

            <div class="practical-box">
              <h6>Practical Example: Office Security</h6>
              "Create a system where an employee stands in front of a camera;
              the AI identifies them within 500ms and automatically opens a
              smart lock (simulated) and logs their name in an Excel sheet with
              a timestamp."
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/sz25MeU_7Ww"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">
              Class 83: Intelligent Voice Assistant (Jarvis-Style)
            </h2>
            <p>
              This project combines **Speech-to-Text (STT)**, **Natural Language
              Understanding (NLU)**, and **Text-to-Speech (TTS)** into a single
              asynchronous loop. Students will use the
              <strong>OpenAI Whisper</strong> model for high-accuracy
              transcriptions even in noisy environments. We explain how to build
              a "Keyword Listener" that keeps the program in low-power mode
              until it hears a wake-word (e.g., "Hey Inspire"). You will learn
              to map voice commands to OS-level Python scripts, allowing the
              assistant to open apps, play music on Spotify, or read the daily
              news. A major focus is on
              <strong>Latency Optimization</strong>—using threading to ensure
              the assistant starts "thinking" while the user is still finishing
              their sentence. We provide clear code snippets for handling
              microphone hardware across Windows and Linux systems to ensure a
              smooth setup for every student.
            </p>

            <div class="practical-box">
              <h6>Practical Example: Hands-Free PC Control</h6>
              "Develop a voice assistant that can search for files on your
              computer or summarize your unread emails when you say 'Summarize
              my morning' while you're busy with other tasks."
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/mB5XAsL6uFs"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">Class 84: AI Creative Studio App</h2>
            <p>
              The final project in this module is a full-stack **AI Image
              Generation Portal**. We integrate
              <strong>Stable Diffusion</strong> or
              <strong>DALL-E 3</strong> into a custom web application. Students
              will learn how to build a "Prompt Gallery" where users can share
              and reuse successful prompts. We dive into the technicalities of
              <strong>Hyper-parameter Tuning</strong>, showing how adjusting
              'CFG Scale' and 'Sampling Steps' changes the artistic style of the
              output. You will also implement <strong>In-painting</strong>,
              allowing users to select a part of an image and tell the AI to
              change only that section (e.g., "Change this shirt color to red").
              To provide ease of deployment, we show how to use
              <strong>Gradio</strong> to create a professional-looking web
              interface in just 10 lines of code, making it easy to share the
              project on your LinkedIn profile.
            </p>

            <div class="practical-box">
              <h6>Practical Example: Marketing Asset Tool</h6>
              "Build an app for a local marketing agency that allows them to
              generate infinite variations of product backgrounds for social
              media ads simply by typing a description."
            </div>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/fNxaJsNG3-s"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h4 class="mb-4 text-info">
              <i class="fas fa-check-double me-2"></i> Project Readiness
              Assessment
            </h4>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: Why do we use 'Embeddings' (128D vectors) in Face
                Recognition instead of comparing raw pixels?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(true)">
                  Vectors are lighting-invariant and mathematically faster to
                  compare.
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Pixels are too colorful for the AI to understand.
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Vectors allow us to change the person's clothes in the image.
                </div>
              </div>
            </div>
            <div id="feedback" class="mt-3 fw-bold"></div>
          </div>

          <div class="d-flex justify-content-between mt-5 mb-5">
            <button
              class="btn btn-outline-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> Previous
            </button>
            <div id="nav-container" class="d-none">
              <button
                class="btn btn-info btn-lg text-white rounded-pill px-5 shadow"
              >
                Next Module: AI Security <i class="fas fa-arrow-right ms-2"></i>
              </button>
            </div>
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
              We don't just teach code; we build engineers. These real-world
              projects are your gateway to the international AI job market.
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p>
              <i class="fas fa-map-marker-alt me-2"></i> Khattak Building,
              Nowshera Cantt
            </p>
            <p><i class="fas fa-phone me-2"></i> 03462345453 (Raheel Ahmad)</p>
          </div>
        </div>
        <hr class="border-secondary" />
        <center class="small opacity-50">
          © 2026 Inspire Tech. Professional AI Certification Track.
        </center>
      </div>
    </footer>

    <script src="/support hub.js">
      let currentQuestion = 1;
      const questions = [
        {
          q: "Why do we use 'Embeddings' (128D vectors) in Face Recognition instead of comparing raw pixels?",
          options: [
            "Vectors are lighting-invariant and mathematically faster to compare.",
            "Pixels are too colorful for the AI to understand.",
            "Vectors allow us to change the person's clothes in the image.",
          ],
          correct: 0,
        },
        {
          q: "What is the primary role of 'LangChain' in our Chatbot project?",
          options: [
            "To design the website buttons",
            "To connect LLMs with external tools like databases and APIs",
            "To make the internet faster",
          ],
          correct: 1,
        },
        {
          q: "In an AI Voice Assistant, what does 'Latency' refer to?",
          options: [
            "The volume of the speakers",
            "The delay between a user's command and the AI's response",
            "The color of the microphone",
          ],
          correct: 1,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__pulse'>✔️ Correct! Project logic confirmed.</span>";
          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__bounceIn'>🎓 Practical Phase Complete! Unlocking next module.</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect. Review the technical detail sections.</span>";
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






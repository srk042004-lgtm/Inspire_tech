<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 24 - Final Capstone Module | Inspire Tech Academy</title>

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
                <a href="23rd Generative AI.php" class="class-item"><i class="fas fa-lock"></i> 23. Generative AI 2</a>
                <a href="24th Final Capstone module.php" class="class-item active"><i class="fas fa-play-circle"></i> 24. Final Capstone Module in AI</a>
                <a href="25th Real projects.php" class="class-item"><i class="fas fa-lock"></i> 25. Real Projects of AI</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="final-badge">
              <i class="fas fa-layer-group me-2"></i> System Design
            </div>
            <h2 class="topic-header">Class 78: Architecting a ChatGPT Clone</h2>
            <p>
              Building a ChatGPT clone requires a deep understanding of
              full-stack AI orchestration. In this class, we break down the
              architecture into three layers: the **Frontend**
              (React/Streamlit), the **Middleware** (FastAPI/LangChain), and the
              **Inference Engine** (OpenAI/Ollama). You will learn how to
              implement <strong>WebSockets</strong> for real-time token
              streaming, so the text appears to "type" itself just like the
              original ChatGPT. We explore
              <strong>Session Management</strong> using Redis to store
              conversation histories and maintain context across different user
              logins. We also dive into <strong>Token Optimization</strong>,
              teaching you how to prune history to stay within the model's
              "Context Window" while minimizing API costs. At Inspire Tech, we
              emphasize the importance of <strong>System Prompts</strong>—the
              hidden instructions that define the AI’s personality and safety
              guardrails. This class provides the blueprint for launching your
              own proprietary AI SaaS platform.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/aircAruvnKk"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="final-badge">
              <i class="fas fa-brain me-2"></i> RAG Implementation
            </div>
            <h2 class="topic-header">
              Class 79: Project - AI PDF Research Assistant
            </h2>
            <p>
              For this project, you will build a
              <strong>Retrieval-Augmented Generation (RAG)</strong> application
              that allows users to "talk" to their local documents. You will
              learn to implement <strong>Document Chunking</strong>, where large
              PDF files are broken into smaller segments for better context
              retrieval. We use <strong>Vector Embeddings</strong> (via
              HuggingFace or OpenAI) to convert these text chunks into
              high-dimensional vectors stored in a database like
              <strong>ChromaDB</strong>. When a user asks a question, the system
              performs a <strong>Cosine Similarity Search</strong> to find the
              most relevant chunks and feeds them into the LLM as "Source
              Context." This prevents AI hallucinations and ensures the answers
              are strictly based on the uploaded data. At our Nowshera academy,
              we build this as a tool for students and researchers to summarize
              complex research papers instantly, providing a real-world solution
              for data-heavy workflows.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/u_I8S-9asF0"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="final-badge">
              <i class="fas fa-trophy me-2"></i> Grand Capstone
            </div>
            <h2 class="topic-header">
              Class 80: The Ultimate Generative Capstone
            </h2>
            <p>
              The final project is a
              <strong>Multi-Modal AI Workflow Automation Tool</strong>. This
              capstone requires you to integrate Text, Image, and Code
              generation into a single unified application. You will build a
              system where a user describes a business idea, and the AI
              generates a business plan (Text), a brand logo (Image), and a
              landing page (Code) simultaneously. You will implement
              <strong>Parallel API Calls</strong> to reduce latency and use
              <strong>JSON Parsing</strong> to automatically route the AI's
              output into different UI components. This project tests your
              ability to handle complex state management and error handling
              across multiple AI models. At Inspire Tech Academy, this project
              serves as your graduation thesis, proving you are not just a
              coder, but an **AI Solutions Architect** ready to innovate in the
              global tech market. Upon completion, you will have a
              production-ready portfolio piece that stands out to top-tier
              international employers.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/6ORnRAz3gnA"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-graduation-cap me-2"></i> AI Solutions Architect
              Graduation Quiz
            </h3>
            <div class="quiz-progress"><div id="progress-bar"></div></div>

            <div id="quiz-content">
              <h5 id="question-text">
                Q1: In a RAG system, what is used to measure the similarity
                between a user query and stored document chunks?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(true)">
                  Cosine Similarity / Vector Search
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Mean Squared Error
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  One-Hot Encoding
                </div>
              </div>
            </div>
            <div id="feedback" class="mt-3 fw-bold"></div>
          </div>

          <div class="d-flex justify-content-between mt-5 mb-5">
            <button
              class="btn btn-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> Previous Page
            </button>
            <div id="nav-container" class="d-none">
              <button class="btn btn-success btn-lg rounded-pill px-5 shadow">
                Get Your Certificate <i class="fas fa-award ms-2"></i>
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
              Your journey from student to AI Architect ends here. You have
              mastered the most transformative technology of the 21st century.
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
          q: "In a RAG system, what is used to measure the similarity between a user query and stored document chunks?",
          options: [
            "Cosine Similarity / Vector Search",
            "Mean Squared Error",
            "One-Hot Encoding",
          ],
          correct: 0,
        },
        {
          q: "What protocol is used to enable real-time 'streaming' of text from an AI to a web browser?",
          options: [
            "FTP",
            "WebSockets / Server-Sent Events",
            "Simple Mail Transfer Protocol",
          ],
          correct: 1,
        },
        {
          q: "What is the primary purpose of a 'System Prompt' in an AI Chatbot?",
          options: [
            "To store user passwords",
            "To define the AI's behavior, tone, and safety rules",
            "To speed up the internet connection",
          ],
          correct: 1,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        const bar = document.getElementById("progress-bar");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__flash'>✔️ Analysis Complete. Advancing...</span>";
          let progress = (currentQuestion / 3) * 100;
          bar.style.width = progress + "%";

          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__tada'>🎓 ALL MODULES COMPLETE! You are now a certified AI Engineer.</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect! Review the project architecture and try again.</span>";
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






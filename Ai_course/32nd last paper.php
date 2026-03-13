<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Final AI Certification Exam | Inspire Tech</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="../style.css" />
  </head>
  <body class="ai-course-page ai-course-page">
    <div class="container mb-5">
      <div class="row justify-content-center">
        <div class="col-lg-9">
          <div class="exam-card">
            <div id="setup-screen">
              <h2 class="text-center mb-4 text-primary">
                Final AI Certification Exam
              </h2>
              <p class="text-center">
                Please enter your full name as it should appear on your
                certificate.
              </p>
              <input
                type="text"
                id="studentName"
                class="form-control form-control-lg mb-4 bg-dark text-white border-secondary"
                placeholder="Enter Your Name"
              />
              <button
                class="btn btn-primary w-100 btn-lg"
                onclick="startExam()"
              >
                Start Final Exam (30 Questions)
              </button>
            </div>

            <div id="quiz-screen" style="display: none">
              <div
                class="d-flex justify-content-between align-items-center mb-4"
              >
                <h4 id="page-title">Page 1 of 6</h4>
                <span class="badge bg-primary p-2" id="timer"
                  >Time: 100-Class Mastery</span
                >
              </div>
              <div id="question-payload"></div>
              <div class="nav-btns">
                <button
                  id="prevBtn"
                  class="btn btn-outline-secondary px-4"
                  onclick="changePage(-1)"
                  disabled
                >
                  Previous
                </button>
                <button
                  id="nextBtn"
                  class="btn btn-primary px-5"
                  onclick="changePage(1)"
                >
                  Next Page
                </button>
              </div>
            </div>

            <div id="result-area">
              <div id="result-icon" class="mb-3"></div>
              <h1 id="result-status"></h1>
              <p class="fs-4" id="final-score-text"></p>
              <div id="final-action" class="mt-4"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script src="/support hub.js">
      const allQuestions = [
        // Page 1: Basics & Regression
        {
          q: "Which optimization algorithm is used to minimize the cost function?",
          options: [
            "Random Search",
            "Gradient Descent",
            "Binary Search",
            "Heuristic Search",
          ],
          correct: 1,
        },
        {
          q: "What is 'R-Squared' used to measure?",
          options: [
            "Model Speed",
            "Goodness of fit",
            "Memory Usage",
            "Data Size",
          ],
          correct: 1,
        },
        {
          q: "In Python, which library handles DataFrame objects?",
          options: ["NumPy", "OpenCV", "Pandas", "PyTorch"],
          correct: 2,
        },
        {
          q: "Which matrix operation is fundamental to Neural Network layers?",
          options: ["Addition", "Dot Product", "Division", "Integration"],
          correct: 1,
        },
        {
          q: "What does 'Overfitting' mean?",
          options: [
            "High Training Accuracy, Low Test Accuracy",
            "Low Training, High Test",
            "Low both",
            "High both",
          ],
          correct: 0,
        },
        // Page 2: NLP & LLMs
        {
          q: "What does 'RAG' stand for in LLM development?",
          options: [
            "Random Access Generation",
            "Retrieval Augmented Generation",
            "Rapid AI Growth",
            "Realtime API Guide",
          ],
          correct: 1,
        },
        {
          q: "Which architecture is the foundation of GPT models?",
          options: ["RNN", "CNN", "Transformer", "GAN"],
          correct: 2,
        },
        {
          q: "What is 'Tokenization'?",
          options: [
            "Encrypting data",
            "Breaking text into smaller units",
            "Storing vectors",
            "Cleaning images",
          ],
          correct: 1,
        },
        {
          q: "Which vector database is commonly used for AI search?",
          options: ["MySQL", "ChromaDB", "MongoDB", "Excel"],
          correct: 1,
        },
        {
          q: "What is the primary function of the 'Attention Mechanism'?",
          options: [
            "Focusing on relevant parts of input",
            "Stopping the model",
            "Speeding up training",
            "Reducing memory",
          ],
          correct: 0,
        },
        // Page 3: Computer Vision
        {
          q: "Which layer in a CNN is used for feature extraction?",
          options: ["Dense", "Convolutional", "Dropout", "Flatten"],
          correct: 1,
        },
        {
          q: "What is the purpose of 'Max Pooling'?",
          options: [
            "Increase image size",
            "Reduce spatial dimensions",
            "Color correction",
            "None",
          ],
          correct: 1,
        },
        {
          q: "Which library is the standard for image processing in Python?",
          options: ["Matplotlib", "OpenCV", "Pickle", "Seaborn"],
          correct: 1,
        },
        {
          q: "What does YOLO stand for in Object Detection?",
          options: [
            "You Only Look Once",
            "Yearly Output Level One",
            "You Only Learn Often",
            "None",
          ],
          correct: 0,
        },
        {
          q: "What is 'Data Augmentation' used for?",
          options: [
            "Scaling servers",
            "Artificially increasing dataset size",
            "Deleting bad data",
            "Encryption",
          ],
          correct: 1,
        },
        // Page 4: MLOps & Deployment
        {
          q: "What is a 'Dockerfile'?",
          options: [
            "A list of user data",
            "Script to automate container creation",
            "A text document for notes",
            "An AI model",
          ],
          correct: 1,
        },
        {
          q: "Which port does Streamlit usually run on?",
          options: ["8080", "5000", "8501", "3000"],
          correct: 2,
        },
        {
          q: "What is the role of an API?",
          options: [
            "To store data",
            "To let different software communicate",
            "To train models",
            "To show images",
          ],
          correct: 1,
        },
        {
          q: "Why do we use 'Environment Variables'?",
          options: [
            "To store code",
            "To keep secrets (API Keys) out of code",
            "To speed up Python",
            "To fix bugs",
          ],
          correct: 1,
        },
        {
          q: "Which cloud service is used for continuous deployment?",
          options: ["GitHub Actions", "VLC", "Photoshop", "Notepad"],
          correct: 0,
        },
        // Page 5: Professional & Advanced
        {
          q: "What is 'Feature Engineering'?",
          options: [
            "Building hardware",
            "Creating new input features from raw data",
            "Buying GPUs",
            "Writing CSS",
          ],
          correct: 1,
        },
        {
          q: "Which metric is best for imbalanced classification?",
          options: ["Accuracy", "F1-Score", "Mean", "Median"],
          correct: 1,
        },
        {
          q: "What is 'Fine-tuning'?",
          options: [
            "Writing a new model",
            "Adjusting a pre-trained model for specific tasks",
            "Lowering volume",
            "Fixing syntax",
          ],
          correct: 1,
        },
        {
          q: "What is a 'Prompt' in Generative AI?",
          options: [
            "A warning",
            "User input to guide model output",
            "A system error",
            "A type of database",
          ],
          correct: 1,
        },
        {
          q: "What does 'Latency' mean in AI deployment?",
          options: [
            "Model accuracy",
            "Time taken for a prediction",
            "The size of the model",
            "Total cost",
          ],
          correct: 1,
        },
        // Page 6: Ethics & Final logic
        {
          q: "What is 'AI Bias'?",
          options: [
            "Fast processing",
            "Unfair prejudice in model results",
            "A mathematical constant",
            "The weight of a neuron",
          ],
          correct: 1,
        },
        {
          q: "Which Python function loads a saved Pickle file?",
          options: [
            "pickle.dump()",
            "pickle.load()",
            "pickle.open()",
            "pickle.write()",
          ],
          correct: 1,
        },
        {
          q: "What is 'Temperature' in LLM sampling?",
          options: [
            "Physical heat",
            "Randomness/Creativity of output",
            "CPU usage",
            "Speed of light",
          ],
          correct: 1,
        },
        {
          q: "What is an 'Embedding'?",
          options: [
            "A physical chip",
            "Numerical vector representation of text",
            "A type of web link",
            "None",
          ],
          correct: 1,
        },
        {
          q: "Which framework allows building AI Web Apps in pure Python?",
          options: ["React", "Streamlit", "Angular", "Vue"],
          correct: 1,
        },
      ];

      let currentPage = 0;
      let userAnswers = new Array(30).fill(null);
      let name = "";

      function startExam() {
        name = document.getElementById("studentName").value;
        if (!name) return alert("Please enter your name first!");
        document.getElementById("setup-screen").style.display = "none";
        document.getElementById("quiz-screen").style.display = "block";
        renderPage();
      }

      function renderPage() {
        const start = currentPage * 5;
        const end = start + 5;
        const pageQuestions = allQuestions.slice(start, end);
        const container = document.getElementById("question-payload");

        document.getElementById("page-title").innerText =
          `Page ${currentPage + 1} of 6`;

        container.innerHTML = pageQuestions
          .map(
            (q, i) => `
            <div class="q-block">
                <h6>${start + i + 1}. ${q.q}</h6>
                ${q.options
                  .map(
                    (opt, optIdx) => `
                    <label class="option-label">
                        <input type="radio" name="q${start + i}" value="${optIdx}" 
                        ${userAnswers[start + i] == optIdx ? "checked" : ""} 
                        onchange="saveAnswer(${start + i}, ${optIdx})"> ${opt}
                    </label>
                `,
                  )
                  .join("")}
            </div>
        `,
          )
          .join("");

        document.getElementById("prevBtn").disabled = currentPage === 0;
        document.getElementById("nextBtn").innerText =
          currentPage === 5 ? "Submit Final Exam" : "Next Page";
      }

      function saveAnswer(qIdx, val) {
        userAnswers[qIdx] = val;
      }

      function changePage(dir) {
        if (dir === 1 && currentPage === 5) {
          calculateScore();
        } else {
          currentPage += dir;
          renderPage();
          window.scrollTo(0, 0);
        }
      }

      function calculateScore() {
        let score = 0;
        allQuestions.forEach((q, i) => {
          if (userAnswers[i] == q.correct) score++;
        });

        const percent = (score / 30) * 100;
        document.getElementById("quiz-screen").style.display = "none";
        document.getElementById("result-area").style.display = "block";

        const status = document.getElementById("result-status");
        const scoreText = document.getElementById("final-score-text");
        const actionArea = document.getElementById("final-action");

        scoreText.innerText = `Final Score: ${score}/30 (${percent.toFixed(1)}%)`;

        if (percent >= 75) {
          status.innerHTML = '<span class="status-pass">CERTIFIED!</span>';
          const msg = encodeURIComponent(
            `Hi Sir, I am ${name}. I have just completed my AI Advanced Course with a score of ${percent.toFixed(1)}%. Can you please guide me how I can get my Course Completion certificate?`,
          );
          actionArea.innerHTML = `
                <a href="https://wa.me/923462345453?text=${msg}" target="_blank" class="btn btn-success btn-lg px-5 rounded-pill">
                    <i class="fab fa-whatsapp me-2"></i> Contact Instructor for Certificate
                </a>
            `;
        } else {
          status.innerHTML = '<span class="status-fail">RETAKE REQUIRED</span>';
          actionArea.innerHTML = `
                <p>You need 75% to graduate. Review your notes and try again.</p>
                <button class="btn btn-danger btn-lg px-5 rounded-pill" onclick="location.reload()">Restart Exam</button>
            `;
        }
      }
    </script>
  </body>
</html>



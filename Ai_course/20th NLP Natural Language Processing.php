<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 20 - NLP & Conversational AI | Inspire Tech Academy</title>

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
                <a href="19th computer vision 2.php" class="class-item"><i class="fas fa-lock"></i> 19. Computer Vision of AI 2</a>
                <a href="20th NLP Natural Language Processing.php" class="class-item active"><i class="fas fa-play-circle"></i> 20. NLP Natural Language Processing of AI</a>
                <a href="21st LLMS.php" class="class-item"><i class="fas fa-lock"></i> 21. Large Language Models of AI</a>
                


            </div>
        </div>



        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="nlp-badge">
              <i class="fas fa-language me-2"></i> Computational Linguistics
            </div>
            <h2 class="topic-header">Class 61: Introduction to NLP</h2>
            <p>
              Natural Language Processing (NLP) is the intersection of Computer
              Science, AI, and Linguistics, enabling computers to process human
              language in a meaningful way. In this class, we explore why human
              language is difficult for machines due to ambiguity, slang, and
              context. We cover the core tasks of NLP, including Sentiment
              Analysis, Named Entity Recognition (NER), and Language
              Translation. At Inspire Tech, we teach you that NLP is the
              technology driving tools like Google Translate, Siri, and ChatGPT.
              You will learn the difference between rule-based linguistics and
              modern statistical NLP. We emphasize that a machine doesn't "read"
              words; it calculates the statistical probability of word sequences
              based on massive datasets. Understanding this fundamental shift
              from text to math is crucial for anyone looking to build
              intelligent communication systems.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/fNxaJsNG3-s"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="nlp-badge">
              <i class="fas fa-broom me-2"></i> Data Cleaning
            </div>
            <h2 class="topic-header">
              Class 62: Text Pre-processing Techniques
            </h2>
            <p>
              Before an AI can understand text, the data must be cleaned and
              normalized. This class covers the essential "cleaning pipeline,"
              starting with <strong>Tokenization</strong> (breaking sentences
              into words). You will learn <strong>Stopword Removal</strong> to
              filter out common words like "is" and "the" that add no semantic
              value. We dive into <strong>Stemming</strong> and
              <strong>Lemmatization</strong>, which reduce words to their root
              form (e.g., "running" becomes "run"). You will also learn about
              <strong>Part-of-Speech (POS) Tagging</strong>, which helps the AI
              identify nouns, verbs, and adjectives. We use the NLTK and SpaCy
              libraries to automate these tasks. At our academy, we emphasize
              that 80% of an NLP engineer's success depends on the quality of
              this pre-processing step, as noisy text leads to inaccurate
              models.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/X2vAabgKiuM"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="nlp-badge">
              <i class="fas fa-robot me-2"></i> Conversational AI
            </div>
            <h2 class="topic-header">
              Class 63 & 64: Building Intelligent Chatbots
            </h2>
            <p>
              Chatbots are applications designed to simulate human conversation
              through text or voice. In this project, we move from simple
              rule-based "if-then" bots to
              <strong>Intent-based Chatbots</strong>. You will learn how to
              define "Intents" (what the user wants) and "Entities" (specific
              details in the query). We implement a
              <strong>Bag-of-Words</strong> model and a simple Neural Network to
              classify user input into these predefined intents. You will learn
              to manage "Context," allowing the bot to remember what the user
              said in the previous turn. We also discuss
              <strong>Natural Language Understanding (NLU)</strong> versus
              <strong>Natural Language Generation (NLG)</strong>. This project
              serves as a real-world application for businesses in Nowshera,
              showing how AI can automate customer support and appointment
              booking efficiently.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/9KZwR67B7Bo"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="nlp-badge">
              <i class="fas fa-microphone me-2"></i> Audio Intelligence
            </div>
            <h2 class="topic-header">Class 65: Speech-to-Text & Voice AI</h2>
            <p>
              Speech Recognition closes the gap between the spoken word and
              digital text. In this class, we use the
              <code>SpeechRecognition</code> library and Google’s Web Speech API
              to capture live audio from a microphone and convert it into
              strings. You will learn about
              <strong>Acoustic Modeling</strong> and how computers handle
              background noise and accents. We cover
              <strong>Phonemes</strong>—the smallest units of sound—and how AI
              maps these sounds to linguistic tokens. You will build a
              voice-activated assistant that can perform tasks based on your
              spoken commands. At Inspire Tech, we also touch upon
              <strong>Text-to-Speech (TTS)</strong> using libraries like
              <code>pyttsx3</code>, allowing your AI to "talk back" to you. This
              integration of audio and text represents the pinnacle of
              multi-modal AI, creating a truly hands-free interactive
              experience.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/mB5XAsL6uFs"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-graduation-cap me-2"></i> NLP Specialist
              Challenge
            </h3>
            <div class="quiz-progress"><div id="progress-bar"></div></div>

            <div id="quiz-content">
              <h5 id="question-text">
                Q1: What is the process of breaking a sentence down into
                individual words or symbols?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(true)">
                  Tokenization
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Stemming
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Vectorization
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
                Next Page <i class="fas fa-arrow-right ms-2"></i>
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
              Empowering the next generation of AI engineers in Nowshera. From
              computer vision to human language, we master it all.
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
          q: "What is the process of breaking a sentence down into individual words or symbols?",
          options: ["Tokenization", "Stemming", "Vectorization"],
          correct: 0,
        },
        {
          q: "Which technique reduces words like 'playing' or 'played' to their root form 'play'?",
          options: ["Tagging", "Lemmatization / Stemming", "Tokenization"],
          correct: 1,
        },
        {
          q: "What does NLU stand for in the context of Chatbots?",
          options: [
            "Natural Language Understanding",
            "Neural Logic Unit",
            "Network Language Update",
          ],
          correct: 0,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        const bar = document.getElementById("progress-bar");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__flash'>✔️ Correct! Proceeding...</span>";
          let progress = (currentQuestion / 3) * 100;
          bar.style.width = progress + "%";

          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__tada'>🚀 NLP Module Complete! Unlocking Advanced LLMs...</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
              setTimeout(() => {
                alert(
                  "Great Job! You've passed the NLP basics. Click Next to explore Transformers and GPT.",
                );
              }, 1000);
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect! Try reading the section again.</span>";
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






<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 16 - Vision & Sequences | Inspire Tech Academy</title>

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
                
                <a href="15th Deep learning.php" class="class-item"><i class="fas fa-lock"></i> 15. Deep Learning of AI</a>
                <a href="16th deep Leaning 2 .php" class="class-item active"><i class="fas fa-play-circle"></i> 16. Deep Learning of AI 2</a>
                <a href="17th 3 Deep learning.php" class="class-item"><i class="fas fa-lock"></i> 17. Deep Learning of AI 3</a>
                


            </div>
        </div>

        
         

        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <div class="deep-badge">
              <i class="fas fa-camera me-2"></i> Computer Vision
            </div>
            <h2 class="topic-header">
              Class 44: Convolutional Neural Networks (CNN)
            </h2>
            <p>
              Convolutional Neural Networks (CNNs) are the industry standard for
              processing data with a grid-like topology, such as images. Unlike
              standard ANNs, CNNs use a mathematical operation called
              <strong>Convolution</strong>, where a small "filter" slides across
              the image to detect features like edges, textures, and patterns.
              This architecture is spatially invariant, meaning it can recognize
              a cat whether it is in the top-left or bottom-right corner of a
              photo. Key components include <strong>Pooling Layers</strong>,
              which reduce the data dimensions to speed up processing, and
              <strong>Flattening</strong>, which prepares the data for final
              classification. At Inspire Tech, we explore how CNNs mimic the
              human visual cortex, learning hierarchical features where lower
              layers see lines and deeper layers see complex objects like eyes
              or wheels. Understanding CNNs is the core of modern AI
              technologies like facial recognition, medical X-ray analysis, and
              self-driving car vision systems.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/YRhxdVk_sIs"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="deep-badge">
              <i class="fas fa-flask me-2"></i> Vision Lab
            </div>
            <h2 class="topic-header">
              Class 45: Project - Real-time Object Classifier
            </h2>
            <p>
              In this lab, we build a CNN from scratch to classify images into
              different categories using the CIFAR-10 or MNIST datasets. You
              will learn how to design the <strong>Convolutional Block</strong>,
              including the number of filters and the kernel size. We dive into
              <strong>Data Augmentation</strong> techniques like rotation,
              flipping, and zooming to artificially increase the size of our
              training data and prevent overfitting. You will implement
              <strong>Dropout</strong> layers to make the network more robust
              and <strong>Batch Normalization</strong> to stabilize the training
              process. This project teaches you how to handle three-dimensional
              tensors (height, width, color channels) and interpret the model's
              accuracy using a per-class breakdown. By the end of this class,
              you will have a trained model that can "see" and identify objects,
              which is a significant milestone in any AI engineer's career. At
              our Nowshera lab, we emphasize optimizing these models for
              real-time speed.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/j-3vuBynnOE"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="deep-badge">
              <i class="fas fa-history me-2"></i> Sequential Data
            </div>
            <h2 class="topic-header">
              Class 46: Recurrent Neural Networks (RNN)
            </h2>
            <p>
              Recurrent Neural Networks (RNNs) are designed for data that comes
              in sequences, where the order of information matters, such as
              time-series, speech, or text. Unlike standard networks that treat
              every input independently, RNNs have a "Memory" called a
              <strong>Hidden State</strong> that carries information from
              previous time steps to the current one. This makes them perfect
              for understanding the context of a sentence or predicting the next
              word in a sequence. However, standard RNNs suffer from the
              <strong>Vanishing Gradient Problem</strong>, which makes it hard
              for them to remember long-term dependencies. To solve this, we
              introduce <strong>LSTM (Long Short-Term Memory)</strong> and
              <strong>GRU</strong> architectures, which use "gates" to decide
              what information to keep and what to forget. At Inspire Tech, we
              teach you that RNNs are the reason your smartphone knows what you
              are going to type next and how Google Translate understands the
              grammar of different languages.
            </p>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/AsNTP8Kwu80"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <div class="deep-badge">
              <i class="fas fa-terminal me-2"></i> NLP Lab
            </div>
            <h2 class="topic-header">Class 47: Project - AI Text Generator</h2>
            <p>
              This project puts RNNs to work by building a model that can
              generate text character-by-character or word-by-word. We use
              <strong>Word Embeddings</strong> (like Word2Vec or GloVe) to
              represent words as dense vectors where similar words have similar
              mathematical coordinates. You will learn the
              <strong>Many-to-One</strong> and
              <strong>Many-to-Many</strong> architectural patterns, which are
              the foundations of sentiment analysis and machine translation. We
              train our model on a dataset of literature or code to see how it
              learns the style and structure of the input text. This class also
              covers <strong>Softmax Temperature</strong>, a technique to
              control how "creative" or "predictable" the AI's generated text
              becomes. This project is your first step toward understanding
              Large Language Models (LLMs) like GPT. By the end of this session,
              you will be able to build an AI that can write sentences, poetry,
              or even simple code snippets based on the patterns it learned from
              the training data.
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
              <i class="fas fa-key me-2"></i> Specialized AI Gatekeeper
            </h3>
            <div class="quiz-progress"><div id="progress-bar"></div></div>

            <div id="quiz-content">
              <h5 id="question-text">
                Q1: Which layer in a CNN is responsible for detecting edges and
                textures?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Pooling Layer
                </div>
                <div class="quiz-option" onclick="checkAnswer(true)">
                  Convolutional Layer
                </div>
                <div class="quiz-option" onclick="checkAnswer(false)">
                  Flattening Layer
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
              <i class="fas fa-arrow-left me-2"></i> Deep Learning
            </button>
            <button class="btn btn-success btn-lg rounded-pill px-5 shadow">
              Final Module: Deployment <i class="fas fa-arrow-right ms-2"></i>
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
              Mastering the eyes and ears of AI. From pixels to prose, we build
              the models that define the modern era.
            </p>
          </div>
          <div class="col-md-6 text-md-end">
            <p>Location: Khattak Building, Nowshera Cantt</p>
            <p>Contact: 03462345453</p>
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
          q: "Which layer in a CNN is responsible for detecting edges and textures?",
          options: ["Pooling Layer", "Convolutional Layer", "Flattening Layer"],
          correct: 1,
        },
        {
          q: "What component gives an RNN its 'Memory' to understand sequences?",
          options: ["Hidden State", "ReLu Function", "Max Pooling"],
          correct: 0,
        },
        {
          q: "Which specific architecture was created to solve the 'Vanishing Gradient' problem in standard RNNs?",
          options: ["Perceptrons", "Dense Layers", "LSTM / GRU"],
          correct: 2,
        },
      ];

      function checkAnswer(isCorrect) {
        const feedback = document.getElementById("feedback");
        const bar = document.getElementById("progress-bar");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__flash'>✔️ Correct! Knowledge Verified.</span>";
          let progress = (currentQuestion / 3) * 100;
          bar.style.width = progress + "%";

          setTimeout(() => {
            currentQuestion++;
            if (currentQuestion <= 3) {
              loadQuestion();
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info animate__animated animate__tada'>🚀 Advanced Level Unlocked! Moving to Final Capstone...</span>";
              document
                .getElementById("nav-container")
                .classList.remove("d-none");
            }
          }, 1200);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect! Returning to start of module.</span>";
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






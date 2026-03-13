<!doctype html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>AI Class 6 - Data Structures | Inspire Tech Academy</title>

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
                <a href="5th Modules.php" class="class-item"><i class="fas fa-lock"></i> 05. Modules</a>
                <a href="6th data structure and functions.php" class="class-item active"><i class="fas fa-lock"></i> 06. Data Structure and Functions</a>
                <a href="7th Module and OOP option.php" class="class-item"><i class="fas fa-lock"></i> 07. Module and OOP Options</a>
                


            </div>
        </div>

        <div class="col-lg-9">
            <div class="content-card animate__animated animate__fadeIn">
                <h2 class="topic-header">Class 6: Data Structures and Functions</h2>
                <p>
                    In this class, we will cover the fundamental data structures in Python, including lists, tuples, and dictionaries, which are essential for organizing and managing data in AI applications. We will also explore how to create and use functions to write reusable code, making your programs more efficient and easier to maintain. Understanding these concepts is crucial for any aspiring AI developer, as they form the building blocks for more complex algorithms and systems.
                </p>

                <pre><code># Example of a function that takes a list as input

def calculate_average(numbers_list):
    total = sum(numbers_list)
    average = total / len(numbers_list)
    return average

numbers = [10, 20, 30, 40, 50]
avg = calculate_average(numbers)
print("Average:", avg)</code></pre>

        <div class="col-lg-9">
          <div class="content-card animate__animated animate__fadeIn">
            <h2 class="topic-header">
              Class 14: Python Functions (Reusable Logic)
            </h2>
            <p>
              Functions are blocks of code that only run when they are called,
              allowing you to wrap a specific task into a single package that
              can be used repeatedly throughout your program. Instead of
              rewriting the same 10 lines of code every time you need to
              calculate an AI's accuracy, you can create a function using the
              <code>def</code> keyword and simply call its name. Functions can
              accept "parameters," which are pieces of data passed into them to
              change how they behave, and they can "return" a result back to the
              main program. This practice, known as DRY (Don't Repeat Yourself),
              makes your code cleaner, easier to debug, and much more
              professional. In large-scale AI development, functions allow
              different team members to work on separate parts of the system
              without interfering with each other. At Inspire Tech, we teach you
              that a good function should do one thing and do it perfectly,
              serving as a reliable building block for your software
              architecture.
            </p>

            <pre><code>def greet_student(name):
    return "Welcome to AI Class, " + name

print(greet_student("Raheel"))</code></pre>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/9Os0o3wzS_I"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">
              Class 15: Python Lists (Ordered Collections)
            </h2>
            <p>
              A <strong>List</strong> is one of the most versatile data
              structures in Python, acting as an ordered container that can hold
              multiple items of different types in a single variable. Lists are
              defined using square brackets <code>[]</code> and are "mutable,"
              meaning you can add, remove, or change items even after the list
              has been created. Each item in a list has an "index" or position
              number, starting from 0, which allows you to access specific data
              instantly. In Artificial Intelligence, lists are fundamental
              because they are used to store series of sensor data, lists of
              image pixels, or historical price points for prediction. You can
              use built-in methods like <code>.append()</code> to grow your list
              dynamically or <code>.sort()</code> to organize your data
              alphabetically or numerically. Mastering lists allows you to
              manage collections of information efficiently, which is the first
              step toward handling the "Big Data" required for modern machine
              learning algorithms.
            </p>

            <pre><code>ai_tools = ["ChatGPT", "Gemini", "Claude"]
ai_tools.append("Midjourney")
print(ai_tools[0]) # Output: ChatGPT</code></pre>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/9OeznAkyQz4"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">
              Class 16: Tuples (Immutable Collections)
            </h2>
            <p>
              A <strong>Tuple</strong> is very similar to a list in that it
              stores an ordered sequence of items, but with one critical
              difference: Tuples are "immutable," meaning once they are created,
              they cannot be changed. You define a tuple using parentheses
              <code>()</code> instead of square brackets, and because they
              cannot be modified, they are processed faster by the computer and
              are safer from accidental changes. This makes tuples ideal for
              storing data that should remain constant throughout the program,
              such as geographical coordinates (latitude and longitude) or the
              RGB values of a specific color. In AI development, tuples are
              frequently used to return multiple values from a single function
              or to store the fixed "shape" of a data array. By using tuples,
              you signal to other programmers that this data is a fixed "record"
              rather than a flexible list. Understanding when to use a tuple
              instead of a list is a sign of a mature programmer who cares about
              memory efficiency and data integrity.
            </p>

            <pre><code># Fixed coordinates that shouldn't change
location = (34.01, 71.97) 
print(location[0])</code></pre>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/NI26dqhs2Rk"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div class="content-card">
            <h2 class="topic-header">
              Class 17: Dictionaries (Key-Value Pairs)
            </h2>
            <p>
              A <strong>Dictionary</strong> is a powerful data structure that
              stores information in "Key-Value" pairs, similar to a real-world
              phonebook where you look up a name (the key) to find a number (the
              value). Dictionaries are defined with curly braces
              <code>{}</code> and allow for extremely fast data retrieval
              because you don't need to know the index number; you just need the
              unique key. This makes them the perfect choice for storing complex
              data like user profiles, where keys might be "username," "email,"
              and "grade." In AI and Web APIs, data is almost always transferred
              in a format called JSON, which looks and behaves exactly like a
              Python dictionary. You can easily update a value by calling its
              key, making dictionaries dynamic and highly flexible for real-time
              applications. At Inspire Tech, we emphasize dictionaries because
              they are the bridge between raw data and structured information,
              allowing your AI to "know" which piece of data belongs to which
              category instantly.
            </p>

            <pre><code>student = {
    "name": "Zeeshan",
    "course": "AI Mastery",
    "score": 95
}
print(student["course"])</code></pre>

            <div class="video-box">
              <iframe
                src="https://www.youtube.com/embed/daefaLgNkw0"
                frameborder="0"
                allowfullscreen
              ></iframe>
            </div>
          </div>

          <div id="quiz-box">
            <h3 class="text-info mb-4">
              <i class="fas fa-database me-2"></i> Data Structure Quiz
            </h3>
            <div id="quiz-content">
              <h5 id="question-text">
                Q1: Which data structure should you use if you want to store
                items that CANNOT be changed later?
              </h5>
              <div id="options-container" class="mt-3">
                <div class="quiz-option" onclick="processQuiz(false)">List</div>
                <div class="quiz-option" onclick="processQuiz(true)">Tuple</div>
                <div class="quiz-option" onclick="processQuiz(false)">
                  Dictionary
                </div>
              </div>
              <div id="feedback" class="mt-3 fw-bold"></div>
            </div>
          </div>

          <div class="d-flex justify-content-between mt-5 mb-5">
            <button
              class="btn btn-secondary btn-lg rounded-pill px-4"
              onclick="history.back()"
            >
              <i class="fas fa-arrow-left me-2"></i> Previous Class
            </button>
            <button class="btn btn-primary btn-lg rounded-pill px-5 shadow">
              Next Class <i class="fas fa-arrow-right ms-2"></i>
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
              From basic variables to complex AI structures, we guide you every
              step of the way in Nowshera's premier IT hub.
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

    <script src="/support-hub.js">
      let step = 1;
      function processQuiz(isCorrect) {
        const feedback = document.getElementById("feedback");
        const questionText = document.getElementById("question-text");
        const optionsContainer = document.getElementById("options-container");

        if (isCorrect) {
          feedback.innerHTML =
            "<span class='text-success animate__animated animate__bounceIn'>✔️ Correct! Tuples are fixed.</span>";
          setTimeout(() => {
            if (step === 1) {
              step = 2;
              questionText.innerText =
                "Q2: In a Dictionary, what do we call the unique identifier used to look up a value?";
              optionsContainer.innerHTML = `
                        <div class="quiz-option" onclick="processQuiz(false)">Index</div>
                        <div class="quiz-option" onclick="processQuiz(true)">Key</div>
                        <div class="quiz-option" onclick="processQuiz(false)">Variable</div>
                    `;
              feedback.innerText = "";
            } else {
              feedback.innerHTML =
                "<span class='text-info'>🎉 Excellent! You've mastered Python Data Structures.</span>";
              optionsContainer.innerHTML = `<div class='alert alert-success mt-3'>Module Complete: Data Structures & Functions.</div>`;
            }
          }, 1800);
        } else {
          feedback.innerHTML =
            "<span class='text-danger animate__animated animate__shakeX'>❌ Incorrect! Hint: Think about 'Immutable' data. Try again.</span>";
        }
      }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>






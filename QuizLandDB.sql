DROP DATABASE QuizLand;

Go

CREATE DATABASE QuizLand;

USE QuizLand;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL DEFAULT 'student',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    category VARCHAR(255) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    created_by INT NOT NULL,
    CONSTRAINT fk_quizzes_created_by
        FOREIGN KEY (created_by) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);
CREATE INDEX idx_quizzes_created_by ON quizzes(created_by);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL,
    question_text TEXT NOT NULL,
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') NOT NULL DEFAULT 'multiple_choice',
    difficulty ENUM('easy', 'medium', 'hard') NOT NULL DEFAULT 'medium',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_questions_quiz
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
CREATE INDEX idx_questions_quiz_id ON questions(quiz_id);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL,
    answer_text TEXT NOT NULL,
    is_correct BOOLEAN NOT NULL DEFAULT FALSE,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_answers_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT uq_answers_question_text
        UNIQUE (question_id, id)
);

CREATE TABLE quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    quiz_id INT NOT NULL,
    score INT DEFAULT 0,
    total_questions INT DEFAULT 0,
    attempted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_attempts_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_attempts_quiz
        FOREIGN KEY (quiz_id) REFERENCES quizzes(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE
);
CREATE INDEX idx_attempts_user_quiz ON quiz_attempts(user_id, quiz_id);

CREATE TABLE student_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_attempt_id INT NOT NULL,
    question_id INT NOT NULL,
    selected_answer INT NOT NULL,
    CONSTRAINT fk_sa_attempt
        FOREIGN KEY (quiz_attempt_id) REFERENCES quiz_attempts(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_sa_question
        FOREIGN KEY (question_id) REFERENCES questions(id)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT fk_sa_selected
        FOREIGN KEY (selected_answer) REFERENCES answers(id)
        ON UPDATE CASCADE
        ON DELETE RESTRICT,
    CONSTRAINT uq_attempt_question UNIQUE (quiz_attempt_id, question_id)
);
CREATE INDEX idx_sa_attempt ON student_answers(quiz_attempt_id);

select * from student_answers

-- عشان ادرج الاختبارات والاسئلة جمل  insert  بدل الاضافة من صفحة الادمن 
USE QuizLand;
SET NAMES utf8mb4;
START TRANSACTION;

-- 0) ضمان وجود مُنشئ للاختبارات (لتفادي FK #1452)
INSERT INTO users (username, email, password, role, is_active)
SELECT 'seed_creator', 'seed@example.com', 'seed_only_for_fk', 'admin', 1
WHERE NOT EXISTS (SELECT 1 FROM users);

-- استخدم أول مستخدم موجود كمنشئ
SET @creator := (SELECT id FROM users ORDER BY id LIMIT 1);

-- ===== 1) إدراج الاختبارات العشرة =====
INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الرياضيات','اختبار في الرياضيات الأساسية','رياضيات', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الرياضيات');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار التاريخ','اختبار في التاريخ العالمي','تاريخ', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار التاريخ');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار العلوم','اختبار في العلوم الطبيعية','علوم', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار العلوم');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الجغرافيا','اختبار في الجغرافيا العالمية','جغرافيا', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الجغرافيا');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الأدب العربي','اختبار في الأدب العربي','أدب', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الأدب العربي');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار البرمجة','اختبار في البرمجة بلغة PHP','برمجة', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار البرمجة');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الفيزياء','اختبار في الفيزياء العامة','فيزياء', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الفيزياء');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الكيمياء','اختبار في الكيمياء العامة','كيمياء', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الكيمياء');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار اللغة الإنجليزية','اختبار في اللغة الإنجليزية','لغات', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار اللغة الإنجليزية');

INSERT INTO quizzes (title, description, category, created_by)
SELECT 'اختبار الثقافة العامة','اختبار في الثقافة العامة','ثقافة', @creator
WHERE NOT EXISTS (SELECT 1 FROM quizzes WHERE title='اختبار الثقافة العامة');



/* ========= 2) أسئلة + إجابات (كلها easy) =========
   النمط نفسه لكل سؤال:
   - INSERT سؤال مع NOT EXISTS
   - INSERT إجابات عبر جدول مشتق مسمّى AS x (بدون أقواس أعمدة بعد alias)
*/

-- ===================== 1) اختبار الرياضيات =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار الرياضيات' LIMIT 1);

-- 1
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما هو الجذر التربيعي لـ 625؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما هو الجذر التربيعي لـ 625؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '25' AS answer_text,1 AS is_correct
      UNION ALL SELECT '20',0
      UNION ALL SELECT '30',0
      UNION ALL SELECT '15',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما هو الجذر التربيعي لـ 625؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 2
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'كم عدد الأوجه في مكعب؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='كم عدد الأوجه في مكعب؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '6 أوجه' AS answer_text,1 AS is_correct
      UNION ALL SELECT '4 أوجه',0
      UNION ALL SELECT '8 أوجه',0
      UNION ALL SELECT '5 أوجه',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='كم عدد الأوجه في مكعب؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 3
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ناتج 24 × 15 = ؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ناتج 24 × 15 = ؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '360' AS answer_text,1 AS is_correct
      UNION ALL SELECT '340',0
      UNION ALL SELECT '380',0
      UNION ALL SELECT '400',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ناتج 24 × 15 = ؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 4
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'كم يساوي 2³ ؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='كم يساوي 2³ ؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '8' AS answer_text,1 AS is_correct
      UNION ALL SELECT '6',0
      UNION ALL SELECT '4',0
      UNION ALL SELECT '9',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='كم يساوي 2³ ؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 5
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'إذا كانت مساحة مستطيل 36 م² وطوله 6 م، فما عرضه؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='إذا كانت مساحة مستطيل 36 م² وطوله 6 م، فما عرضه؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '6 م' AS answer_text,1 AS is_correct
      UNION ALL SELECT '5 م',0
      UNION ALL SELECT '7 م',0
      UNION ALL SELECT '8 م',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='إذا كانت مساحة مستطيل 36 م² وطوله 6 م، فما عرضه؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 6
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما هو الجذر التكعيبي لـ 1000؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما هو الجذر التكعيبي لـ 1000؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '10' AS answer_text,1 AS is_correct
      UNION ALL SELECT '5',0
      UNION ALL SELECT '12',0
      UNION ALL SELECT '15',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما هو الجذر التكعيبي لـ 1000؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 7
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'إذا كان مجموع زاويتين في مثلث 90°، فما الزاوية الثالثة؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='إذا كان مجموع زاويتين في مثلث 90°، فما الزاوية الثالثة؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '90°' AS answer_text,1 AS is_correct
      UNION ALL SELECT '60°',0
      UNION ALL SELECT '45°',0
      UNION ALL SELECT '30°',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='إذا كان مجموع زاويتين في مثلث 90°، فما الزاوية الثالثة؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 8
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما القيمة التقريبية لـ π؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما القيمة التقريبية لـ π؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '3.14' AS answer_text,1 AS is_correct
      UNION ALL SELECT '2.71',0
      UNION ALL SELECT '3.24',0
      UNION ALL SELECT '3.4',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما القيمة التقريبية لـ π؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 9
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أصغر مضاعف مشترك بين 6 و 8 هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أصغر مضاعف مشترك بين 6 و 8 هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '24' AS answer_text,1 AS is_correct
      UNION ALL SELECT '12',0
      UNION ALL SELECT '18',0
      UNION ALL SELECT '30',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أصغر مضاعف مشترك بين 6 و 8 هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

-- 10
INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'حل المعادلة 3x + 5 = 20','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='حل المعادلة 3x + 5 = 20');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'x = 5' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'x = 3',0
      UNION ALL SELECT 'x = 7',0
      UNION ALL SELECT 'x = 10',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='حل المعادلة 3x + 5 = 20'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 2) اختبار التاريخ =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار التاريخ' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'من هو مكتشف أمريكا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='من هو مكتشف أمريكا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'كريستوفر كولومبوس' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'فاسكو دا جاما',0
      UNION ALL SELECT 'ماجلان',0
      UNION ALL SELECT 'ابن بطوطة',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='من هو مكتشف أمريكا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'في أي عام انتهت الحرب العالمية الثانية؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='في أي عام انتهت الحرب العالمية الثانية؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '1945' AS answer_text,1 AS is_correct
      UNION ALL SELECT '1939',0
      UNION ALL SELECT '1918',0
      UNION ALL SELECT '1950',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='في أي عام انتهت الحرب العالمية الثانية؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما اسم الكتابة عند المصريين القدماء؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما اسم الكتابة عند المصريين القدماء؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الهيروغليفية' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'المسمارية',0
      UNION ALL SELECT 'اللاتينية',0
      UNION ALL SELECT 'العبرية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما اسم الكتابة عند المصريين القدماء؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عاصمة الدولة العثمانية كانت؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عاصمة الدولة العثمانية كانت؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'إسطنبول' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'أنقرة',0
      UNION ALL SELECT 'القاهرة',0
      UNION ALL SELECT 'دمشق',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عاصمة الدولة العثمانية كانت؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أول خليفة للمسلمين هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أول خليفة للمسلمين هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'أبو بكر الصديق' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'عمر بن الخطاب',0
      UNION ALL SELECT 'عثمان بن عفان',0
      UNION ALL SELECT 'علي بن أبي طالب',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أول خليفة للمسلمين هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'سور الصين العظيم يقع في؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='سور الصين العظيم يقع في؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الصين' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الهند',0
      UNION ALL SELECT 'كوريا',0
      UNION ALL SELECT 'اليابان',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='سور الصين العظيم يقع في؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'مؤلف "المقدمة" هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='مؤلف "المقدمة" هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'ابن خلدون' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الطبري',0
      UNION ALL SELECT 'ابن كثير',0
      UNION ALL SELECT 'المسعودي',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='مؤلف "المقدمة" هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'بدأت الثورة الصناعية في؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='بدأت الثورة الصناعية في؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'بريطانيا' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'فرنسا',0
      UNION ALL SELECT 'ألمانيا',0
      UNION ALL SELECT 'الولايات المتحدة',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='بدأت الثورة الصناعية في؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'قائد فتح الأندلس هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='قائد فتح الأندلس هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'طارق بن زياد' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'عمرو بن العاص',0
      UNION ALL SELECT 'خالد بن الوليد',0
      UNION ALL SELECT 'صلاح الدين الأيوبي',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='قائد فتح الأندلس هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الحضارة التي بنت الأهرامات؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الحضارة التي بنت الأهرامات؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'المصرية القديمة' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الآشورية',0
      UNION ALL SELECT 'اليونانية',0
      UNION ALL SELECT 'الرومانية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الحضارة التي بنت الأهرامات؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 3) اختبار العلوم =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار العلوم' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما الصيغة الكيميائية للماء؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما الصيغة الكيميائية للماء؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'H2O' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'CO2',0
      UNION ALL SELECT 'O2',0
      UNION ALL SELECT 'H2',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما الصيغة الكيميائية للماء؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الكوكب الملقب بالكوكب الأحمر؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الكوكب الملقب بالكوكب الأحمر؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'المريخ' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'عطارد',0
      UNION ALL SELECT 'الزهرة',0
      UNION ALL SELECT 'المشتري',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الكوكب الملقب بالكوكب الأحمر؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أكبر عضو في جسم الإنسان؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أكبر عضو في جسم الإنسان؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الجلد' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الكبد',0
      UNION ALL SELECT 'القلب',0
      UNION ALL SELECT 'الدماغ',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أكبر عضو في جسم الإنسان؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'تحدث عملية البناء الضوئي في؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='تحدث عملية البناء الضوئي في؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'البلاستيدات الخضراء' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الميتوكوندريا',0
      UNION ALL SELECT 'النواة',0
      UNION ALL SELECT 'الرايبوسومات',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='تحدث عملية البناء الضوئي في؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الخلايا التي تنقل الأكسجين هي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الخلايا التي تنقل الأكسجين هي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'كريات الدم الحمراء' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الصفائح الدموية',0
      UNION ALL SELECT 'كريات الدم البيضاء',0
      UNION ALL SELECT 'خلايا عصبية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الخلايا التي تنقل الأكسجين هي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'درجة غليان الماء (°م)؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='درجة غليان الماء (°م)؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '100' AS answer_text,1 AS is_correct
      UNION ALL SELECT '90',0
      UNION ALL SELECT '80',0
      UNION ALL SELECT '120',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='درجة غليان الماء (°م)؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أكثر غاز في الغلاف الجوي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أكثر غاز في الغلاف الجوي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'النيتروجين' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الأكسجين',0
      UNION ALL SELECT 'ثاني أكسيد الكربون',0
      UNION ALL SELECT 'الأرجون',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أكثر غاز في الغلاف الجوي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'وحدة قياس شدة التيار الكهربائي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='وحدة قياس شدة التيار الكهربائي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الأمبير' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الفولت',0
      UNION ALL SELECT 'الأوم',0
      UNION ALL SELECT 'الواط',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='وحدة قياس شدة التيار الكهربائي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ما الجهاز الذي يضخ الدم في الجسم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ما الجهاز الذي يضخ الدم في الجسم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'القلب' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'المعدة',0
      UNION ALL SELECT 'الكبد',0
      UNION ALL SELECT 'الرئتان',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ما الجهاز الذي يضخ الدم في الجسم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عدد عظام الإنسان البالغ تقريبًا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عدد عظام الإنسان البالغ تقريبًا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '206' AS answer_text,1 AS is_correct
      UNION ALL SELECT '180',0
      UNION ALL SELECT '250',0
      UNION ALL SELECT '300',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عدد عظام الإنسان البالغ تقريبًا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 4) اختبار الجغرافيا =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار الجغرافيا' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أكبر محيط على سطح الأرض؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أكبر محيط على سطح الأرض؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'المحيط الهادئ' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'المحيط الأطلسي',0
      UNION ALL SELECT 'المحيط الهندي',0
      UNION ALL SELECT 'المحيط المتجمد الشمالي',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أكبر محيط على سطح الأرض؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أعلى جبل في العالم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أعلى جبل في العالم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'جبل إيفرست' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'كيليمانجارو',0
      UNION ALL SELECT 'مون بلان',0
      UNION ALL SELECT 'جبل فوجي',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أعلى جبل في العالم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عاصمة اليابان هي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عاصمة اليابان هي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'طوكيو' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'أوساكا',0
      UNION ALL SELECT 'كيوتو',0
      UNION ALL SELECT 'ناغويا',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عاصمة اليابان هي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أكبر صحراء حارة؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أكبر صحراء حارة؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الصحراء الكبرى' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'صحراء أتاكاما',0
      UNION ALL SELECT 'صحراء جوبي',0
      UNION ALL SELECT 'صحراء كالاهاري',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أكبر صحراء حارة؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'النهر الذي يمر عبر مصر؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='النهر الذي يمر عبر مصر؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'النيل' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الفرات',0
      UNION ALL SELECT 'الدانوب',0
      UNION ALL SELECT 'الأمازون',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='النهر الذي يمر عبر مصر؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عاصمة كندا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عاصمة كندا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'أوتاوا' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'تورونتو',0
      UNION ALL SELECT 'فانكوفر',0
      UNION ALL SELECT 'مونتريال',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عاصمة كندا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عاصمة إسبانيا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عاصمة إسبانيا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'مدريد' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'برشلونة',0
      UNION ALL SELECT 'إشبيلية',0
      UNION ALL SELECT 'فالنسيا',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عاصمة إسبانيا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أكبر قارة في العالم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أكبر قارة في العالم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'آسيا' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'أفريقيا',0
      UNION ALL SELECT 'أوروبا',0
      UNION ALL SELECT 'أمريكا الجنوبية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أكبر قارة في العالم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'بلد يُعرف بأرض الألف بحيرة؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='بلد يُعرف بأرض الألف بحيرة؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'فنلندا' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'السويد',0
      UNION ALL SELECT 'النرويج',0
      UNION ALL SELECT 'إستونيا',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='بلد يُعرف بأرض الألف بحيرة؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'البحر الفاصل بين أوروبا وأفريقيا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='البحر الفاصل بين أوروبا وأفريقيا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'البحر الأبيض المتوسط' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'بحر الشمال',0
      UNION ALL SELECT 'بحر العرب',0
      UNION ALL SELECT 'بحر قزوين',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='البحر الفاصل بين أوروبا وأفريقيا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 5) اختبار الأدب العربي =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار الأدب العربي' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'من لُقّب بشاعر النيل؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='من لُقّب بشاعر النيل؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'حافظ إبراهيم' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'أحمد شوقي',0
      UNION ALL SELECT 'محمود درويش',0
      UNION ALL SELECT 'الجواهري',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='من لُقّب بشاعر النيل؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'صاحب ديوان الشوقيات؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='صاحب ديوان الشوقيات؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'أحمد شوقي' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'حافظ إبراهيم',0
      UNION ALL SELECT 'المتنبي',0
      UNION ALL SELECT 'امرؤ القيس',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='صاحب ديوان الشوقيات؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'مؤلف كتاب "الأيام"؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='مؤلف كتاب "الأيام"؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'طه حسين' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'نجيب محفوظ',0
      UNION ALL SELECT 'توفيق الحكيم',0
      UNION ALL SELECT 'العقاد',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='مؤلف كتاب "الأيام"؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'رواية "بين القصرين" من تأليف؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='رواية "بين القصرين" من تأليف؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'نجيب محفوظ' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'إحسان عبد القدوس',0
      UNION ALL SELECT 'يوسف إدريس',0
      UNION ALL SELECT 'بهاء طاهر',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='رواية "بين القصرين" من تأليف؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'واضع علم العَروض؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='واضع علم العَروض؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الخليل بن أحمد الفراهيدي' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'سيبويه',0
      UNION ALL SELECT 'ابن جني',0
      UNION ALL SELECT 'الجرجاني',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='واضع علم العَروض؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'المقامات تُعد من فنون؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='المقامات تُعد من فنون؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'النثر' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الشعر',0
      UNION ALL SELECT 'السيرة',0
      UNION ALL SELECT 'التاريخ',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='المقامات تُعد من فنون؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الطباق في البلاغة هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الطباق في البلاغة هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الجمع بين المتضادين' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'تشبيه شيء بآخر',0
      UNION ALL SELECT 'حذف حرف للوزن',0
      UNION ALL SELECT 'زيادة حرف للقافية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الطباق في البلاغة هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الكناية تُفيد؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الكناية تُفيد؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'التعبير غير المباشر' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'المبالغة',0
      UNION ALL SELECT 'التصريح المباشر',0
      UNION ALL SELECT 'الجناس',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الكناية تُفيد؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'من أصحاب المعلقات؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='من أصحاب المعلقات؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'امرؤ القيس' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'المتنبي',0
      UNION ALL SELECT 'أحمد شوقي',0
      UNION ALL SELECT 'أبو تمام',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='من أصحاب المعلقات؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'أديب عربي حصل على نوبل للآداب؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='أديب عربي حصل على نوبل للآداب؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'نجيب محفوظ' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'طه حسين',0
      UNION ALL SELECT 'محمود درويش',0
      UNION ALL SELECT 'أدونيس',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='أديب عربي حصل على نوبل للآداب؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 6) اختبار البرمجة (PHP) =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار البرمجة' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'امتداد ملفات PHP هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='امتداد ملفات PHP هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '.php' AS answer_text,1 AS is_correct
      UNION ALL SELECT '.ph',0
      UNION ALL SELECT '.phtmlx',0
      UNION ALL SELECT '.html',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='امتداد ملفات PHP هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'متغيرات PHP تبدأ بالرمز؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='متغيرات PHP تبدأ بالرمز؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '$' AS answer_text,1 AS is_correct
      UNION ALL SELECT '#',0
      UNION ALL SELECT '@',0
      UNION ALL SELECT '&',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='متغيرات PHP تبدأ بالرمز؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الدالة المستخدمة للطباعة في PHP؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الدالة المستخدمة للطباعة في PHP؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'echo' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'scanf',0
      UNION ALL SELECT 'println',0
      UNION ALL SELECT 'alert',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الدالة المستخدمة للطباعة في PHP؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'لبدء جلسة نستخدم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='لبدء جلسة نستخدم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'session_start()' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'start_session()',0
      UNION ALL SELECT 'open_session()',0
      UNION ALL SELECT 'session_begin()',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='لبدء جلسة نستخدم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'لحساب عدد عناصر مصفوفة نستخدم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='لحساب عدد عناصر مصفوفة نستخدم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'count()' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'size()',0
      UNION ALL SELECT 'len()',0
      UNION ALL SELECT 'length()',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='لحساب عدد عناصر مصفوفة نستخدم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'امتداد الاتصال الشائع مع MySQL؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='امتداد الاتصال الشائع مع MySQL؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'MySQLi' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'PDO فقط',0
      UNION ALL SELECT 'ODBC',0
      UNION ALL SELECT 'SQLite',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='امتداد الاتصال الشائع مع MySQL؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'عامل المساواة مع فحص النوع؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='عامل المساواة مع فحص النوع؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '===' AS answer_text,1 AS is_correct
      UNION ALL SELECT '==',0
      UNION ALL SELECT '=',0
      UNION ALL SELECT '!==',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='عامل المساواة مع فحص النوع؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'نوع المصفوفة ذات المفاتيح النصية؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='نوع المصفوفة ذات المفاتيح النصية؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'مصفوفة ترابطية' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'مصفوفة رقمية',0
      UNION ALL SELECT 'مصفوفة متعددة الأبعاد',0
      UNION ALL SELECT 'مكدس',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='نوع المصفوفة ذات المفاتيح النصية؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'لإعادة التوجيه في PHP نستخدم؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='لإعادة التوجيه في PHP نستخدم؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'header(''Location: ...'')' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'redirect()',0
      UNION ALL SELECT 'goto()',0
      UNION ALL SELECT 'route()',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='لإعادة التوجيه في PHP نستخدم؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'ملف إعدادات Composer هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='ملف إعدادات Composer هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'composer.json' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'package.json',0
      UNION ALL SELECT 'composer.lock',0
      UNION ALL SELECT 'php.ini',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ملف إعدادات Composer هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 7) اختبار الفيزياء =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار الفيزياء' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'وحدة القوة هي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='وحدة القوة هي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'النيوتن' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الجول',0
      UNION ALL SELECT 'الواط',0
      UNION ALL SELECT 'الأمبير',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='وحدة القوة هي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'سرعة الضوء تقريبًا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='سرعة الضوء تقريبًا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '3×10^8 م/ث' AS answer_text,1 AS is_correct
      UNION ALL SELECT '3×10^6 م/ث',0
      UNION ALL SELECT '3×10^5 كم/ث',0
      UNION ALL SELECT '3×10^4 م/ث',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='سرعة الضوء تقريبًا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'قانون نيوتن الثاني؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='قانون نيوتن الثاني؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'F = m a' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'E = m c^2',0
      UNION ALL SELECT 'V = I R',0
      UNION ALL SELECT 'p = m v',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='قانون نيوتن الثاني؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'طاقة الحركة تُعطى بالعلاقة؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='طاقة الحركة تُعطى بالعلاقة؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '1/2 m v^2' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'm g h',0
      UNION ALL SELECT 'k x',0
      UNION ALL SELECT 'q V',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='طاقة الحركة تُعطى بالعلاقة؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'العدسة المحدبة تقوم بـ؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='العدسة المحدبة تقوم بـ؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'تجميع الأشعة' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'تشتت الأشعة',0
      UNION ALL SELECT 'عكس القطبية',0
      UNION ALL SELECT 'توليد الضوء',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='العدسة المحدبة تقوم بـ؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'جهاز قياس شدة التيار؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='جهاز قياس شدة التيار؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'الأميتر' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الفولتميتر',0
      UNION ALL SELECT 'الأوميتر',0
      UNION ALL SELECT 'الترمومتر',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='جهاز قياس شدة التيار؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'هل ينتقل الصوت في الفراغ؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='هل ينتقل الصوت في الفراغ؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'لا' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'نعم',0
      UNION ALL SELECT 'أحيانًا',0
      UNION ALL SELECT 'إذا كان قويًا',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='هل ينتقل الصوت في الفراغ؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'كمية الحركة تُساوي؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='كمية الحركة تُساوي؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'p = m v' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'p = v / m',0
      UNION ALL SELECT 'p = m / v',0
      UNION ALL SELECT 'p = m + v',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='كمية الحركة تُساوي؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'مقياس شدة الزلازل؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='مقياس شدة الزلازل؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'ريختر' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'بوفورت',0
      UNION ALL SELECT 'سافير-سيمبسون',0
      UNION ALL SELECT 'ديسيبل',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='مقياس شدة الزلازل؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'قانون أوم يكتب؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='قانون أوم يكتب؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'V = I R' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'P = I V',0
      UNION ALL SELECT 'E = m c^2',0
      UNION ALL SELECT 'F = m a',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='قانون أوم يكتب؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 8) اختبار الكيمياء =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار الكيمياء' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'العدد الذري للهيدروجين؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='العدد الذري للهيدروجين؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '1' AS answer_text,1 AS is_correct
      UNION ALL SELECT '2',0
      UNION ALL SELECT '8',0
      UNION ALL SELECT '6',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='العدد الذري للهيدروجين؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'NaCl هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='NaCl هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'كلوريد الصوديوم' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'نترات الصوديوم',0
      UNION ALL SELECT 'كبريتات الصوديوم',0
      UNION ALL SELECT 'كربونات الصوديوم',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='NaCl هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'HCl يُعرف بـ؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='HCl يُعرف بـ؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'حمض الهيدروكلوريك' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'حمض الخليك',0
      UNION ALL SELECT 'حمض الكبريتيك',0
      UNION ALL SELECT 'حمض النتريك',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='HCl يُعرف بـ؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الرقم الهيدروجيني المحايد؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الرقم الهيدروجيني المحايد؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '7' AS answer_text,1 AS is_correct
      UNION ALL SELECT '1',0
      UNION ALL SELECT '14',0
      UNION ALL SELECT '9',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الرقم الهيدروجيني المحايد؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'تفاعل الحمض مع معدن يُطلق غالبًا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='تفاعل الحمض مع معدن يُطلق غالبًا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'غاز الهيدروجين' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'غاز الأكسجين',0
      UNION ALL SELECT 'غاز النيتروجين',0
      UNION ALL SELECT 'غاز الكلور',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='تفاعل الحمض مع معدن يُطلق غالبًا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الرابطة بين فلز ولافلز غالبًا؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الرابطة بين فلز ولافلز غالبًا؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'أيونية' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'تساهمية قطبية',0
      UNION ALL SELECT 'فلزية',0
      UNION ALL SELECT 'هيدروجينية',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الرابطة بين فلز ولافلز غالبًا؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'غاز دفيئة رئيس؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='غاز دفيئة رئيس؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'ثاني أكسيد الكربون' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'الأكسجين',0
      UNION ALL SELECT 'النيون',0
      UNION ALL SELECT 'الهيليوم',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='غاز دفيئة رئيس؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الحالة الرابعة للمادة؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الحالة الرابعة للمادة؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'البلازما' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'البلورية',0
      UNION ALL SELECT 'الزجاجية',0
      UNION ALL SELECT 'المائعة الفائقة',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الحالة الرابعة للمادة؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الكتلة المولية للماء H2O؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الكتلة المولية للماء H2O؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT '18 غ/مول' AS answer_text,1 AS is_correct
      UNION ALL SELECT '16 غ/مول',0
      UNION ALL SELECT '20 غ/مول',0
      UNION ALL SELECT '14 غ/مول',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='الكتلة المولية للماء H2O؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'رمز عنصر الحديد؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='رمز عنصر الحديد؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'Fe' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'H',0
      UNION ALL SELECT 'K',0
      UNION ALL SELECT 'Ag',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='رمز عنصر الحديد؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);



-- ===================== 9) اختبار اللغة الإنجليزية =====================
SET @quiz_id := (SELECT id FROM quizzes WHERE title='اختبار اللغة الإنجليزية' LIMIT 1);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'جمع child هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='جمع child هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'children' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'childs',0
      UNION ALL SELECT 'childes',0
      UNION ALL SELECT 'childrens',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='جمع child هو؟'
  AND NOT EXISTS (SELECT 1 FROM answers a WHERE a.question_id=q.id AND a.answer_text=x.answer_text);

INSERT INTO questions (quiz_id, question_text, question_type, difficulty)
SELECT @quiz_id,'الماضي من go هو؟','multiple_choice','easy'
WHERE @quiz_id IS NOT NULL AND NOT EXISTS (SELECT 1 FROM questions WHERE quiz_id=@quiz_id AND question_text='الماضي من go هو؟');
INSERT INTO answers (question_id, answer_text, is_correct)
SELECT q.id, x.answer_text, x.is_correct FROM questions q
JOIN (SELECT 'went' AS answer_text,1 AS is_correct
      UNION ALL SELECT 'goed',0
      UNION ALL SELECT 'gone',0
      UNION ALL SELECT 'goes',0) AS x
WHERE q.quiz_id=@quiz_id AND q.question_text='ال**

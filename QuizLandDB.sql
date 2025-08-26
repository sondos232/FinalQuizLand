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

INSERT INTO quizzes (title, description, category, created_by) VALUES
('اختبار الرياضيات', 'اختبار في الرياضيات الأساسية', 'رياضيات', 1),
('اختبار التاريخ', 'اختبار في التاريخ العالمي', 'تاريخ', 1),
('اختبار العلوم', 'اختبار في العلوم الطبيعية', 'علوم', 1),
('اختبار الجغرافيا', 'اختبار في الجغرافيا العالمية', 'جغرافيا', 1),
('اختبار الأدب العربي', 'اختبار في الأدب العربي', 'أدب', 1),
('اختبار البرمجة', 'اختبار في البرمجة بلغة PHP', 'برمجة', 1),
('اختبار الفيزياء', 'اختبار في الفيزياء العامة', 'فيزياء', 1),
('اختبار الكيمياء', 'اختبار في الكيمياء العامة', 'كيمياء', 1),
('اختبار اللغة الإنجليزية', 'اختبار في اللغة الإنجليزية', 'لغات', 1),
('اختبار الثقافة العامة', 'اختبار في الثقافة العامة', 'ثقافة', 1);

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

INSERT INTO questions (quiz_id, question_text, question_type, difficulty) VALUES
(1, 'ما هو الجذر التربيعي لـ 625؟', 'multiple_choice', 'hard'),
(1, 'إذا كانت زاويتان في مثلث مجموعهما 90 درجة، فما الزاوية الثالثة؟', 'multiple_choice', 'hard'),
(1, 'كم عدد الأوجه في مكعب؟', 'multiple_choice', 'hard'),
(1, 'ما هو العدد الذي إذا ضرب في نفسه يعطي 144؟', 'multiple_choice', 'hard'),
(1, 'إذا كانت نسبة العرض إلى الطول في مستطيل هي 3:4، فما هو محيط المستطيل إذا كان طوله 12 وحدة؟', 'multiple_choice', 'hard'),
(1, 'كيف تحسب مساحة دائرة نصف قطرها 7 سم؟', 'multiple_choice', 'hard'),
(1, 'ما هو العدد الذي يكمل المتسلسلة التالية: 2, 6, 12, 20, ... ؟', 'multiple_choice', 'hard'),
(1, 'إذا كانت مساحة مثلث 20 متر مربع، وكان طول قاعدته 10 متر، فما ارتفاعه؟', 'multiple_choice', 'hard'),
(1, 'ما هو حاصل ضرب 24 × 15؟', 'multiple_choice', 'hard'),
(1, 'ما هو الجذر التكعيبي لـ 1000؟', 'multiple_choice', 'hard');

INSERT INTO questions (quiz_id, question_text, question_type, difficulty) VALUES
(1, 'ما هو ناتج 100 ÷ 4؟', 'multiple_choice', 'medium'),
(1, 'كم عدد الأضلاع في شكل رباعي؟', 'multiple_choice', 'medium'),
(1, 'ما هو مجموع زوايا مثلث؟', 'multiple_choice', 'medium'),
(1, 'كم عدد الأرقام في العدد 123456؟', 'multiple_choice', 'medium'),
(1, 'إذا كانت مساحة مستطيل 36 متر مربع وطوله 6 متر، فما عرضه؟', 'multiple_choice', 'medium'),
(1, 'ما هو الرقم الذي يأتي بعد 50 في تسلسل الأعداد: 2, 5, 8, 11, ... ؟', 'multiple_choice', 'medium'),
(1, 'كم عدد الأمتار في كيلو متر واحد؟', 'multiple_choice', 'medium'),
(1, 'ما هو حاصل جمع 15 + 25؟', 'multiple_choice', 'medium'),
(1, 'إذا كانت الهرم له قاعدة مربعة طول ضلعها 6 سم، فما مساحتها؟', 'multiple_choice', 'medium'),
(1, 'ما هو قيمة العدد 2³؟', 'multiple_choice', 'medium');

INSERT INTO questions (quiz_id, question_text, question_type, difficulty) VALUES
(1, 'ما هو ناتج 5 + 3؟', 'multiple_choice', 'easy'),
(1, 'كم عدد الكواكب في النظام الشمسي؟', 'multiple_choice', 'easy'),
(1, 'ما هو عاصمة مصر؟', 'multiple_choice', 'easy'),
(1, 'ما هو أكبر حيوان في العالم؟', 'multiple_choice', 'easy'),
(1, 'كم عدد الأيام في الأسبوع؟', 'multiple_choice', 'easy'),
(1, 'من هو أول رئيس للولايات المتحدة الأمريكية؟', 'multiple_choice', 'easy'),
(1, 'ما هو اسم أطول نهر في العالم؟', 'multiple_choice', 'easy'),
(1, 'كم عدد القارات في العالم؟', 'multiple_choice', 'easy'),
(1, 'ما هو أقرب كوكب إلى الشمس؟', 'multiple_choice', 'easy'),
(1, 'كم عدد الساعات في اليوم؟', 'multiple_choice', 'easy');
INSERT INTO questions (quiz_id, question_text, question_type, difficulty) VALUES
(1, 'ما هو أصغر كوكب في النظام الشمسي؟', 'multiple_choice', 'easy'),
(1, 'ما هي عاصمة فرنسا؟', 'multiple_choice', 'easy'),
(1, 'ما هو أطول جبل في العالم؟', 'multiple_choice', 'easy'),
(1, 'كم عدد اللاعبين في فريق كرة القدم؟', 'multiple_choice', 'easy'),
(1, 'من هو أول إنسان سافر إلى الفضاء؟', 'multiple_choice', 'easy'),
(1, 'ما هي اللغة الرسمية في اليابان؟', 'multiple_choice', 'easy'),
(1, 'ما هو أكبر بحر في العالم؟', 'multiple_choice', 'easy'),
(1, 'كم عدد الأسابيع في السنة؟', 'multiple_choice', 'easy'),
(1, 'من هو مكتشف قوانين الحركة؟', 'multiple_choice', 'easy'),
(1, 'ما هو أعلى شلال في العالم؟', 'multiple_choice', 'easy');

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

INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(1, '25', TRUE), (1, '20', FALSE), (1, '30', FALSE), (1, '15', FALSE),
(2, '90 درجة', TRUE), (2, '60 درجة', FALSE), (2, '45 درجة', FALSE), (2, '30 درجة', FALSE),
(3, '6 أوجه', TRUE), (3, '4 أوجه', FALSE), (3, '8 أوجه', FALSE), (3, '5 أوجه', FALSE),
(4, '12', TRUE), (4, '10', FALSE), (4, '14', FALSE), (4, '16', FALSE),
(5, '36 وحدة', TRUE), (5, '48 وحدة', FALSE), (5, '60 وحدة', FALSE), (5, '72 وحدة', FALSE),
(6, '154 سم²', TRUE), (6, '148 سم²', FALSE), (6, '160 سم²', FALSE), (6, '180 سم²', FALSE),
(7, '30', TRUE), (7, '28', FALSE), (7, '32', FALSE), (7, '35', FALSE),
(8, '4 متر', TRUE), (8, '5 متر', FALSE), (8, '6 متر', FALSE), (8, '7 متر', FALSE),
(9, '360', TRUE), (9, '340', FALSE), (9, '380', FALSE), (9, '400', FALSE),
(10, '10', TRUE), (10, '5', FALSE), (10, '12', FALSE), (10, '15', FALSE);

INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(11, '25', TRUE), (11, '20', FALSE), (11, '15', FALSE), (11, '30', FALSE),
(12, '4 أضلاع', TRUE), (12, '6 أضلاع', FALSE), (12, '5 أضلاع', FALSE), (12, '3 أضلاع', FALSE),
(13, '180 درجة', TRUE), (13, '150 درجة', FALSE), (13, '190 درجة', FALSE), (13, '200 درجة', FALSE),
(14, '6', TRUE), (14, '5', FALSE), (14, '4', FALSE), (14, '3', FALSE),
(15, '6 متر', TRUE), (15, '5 متر', FALSE), (15, '7 متر', FALSE), (15, '8 متر', FALSE),
(16, '53', TRUE), (16, '55', FALSE), (16, '52', FALSE), (16, '50', FALSE),
(17, '1000 متر', TRUE), (17, '500 متر', FALSE), (17, '2000 متر', FALSE), (17, '100 متر', FALSE),
(18, '40', TRUE), (18, '30', FALSE), (18, '45', FALSE), (18, '50', FALSE),
(19, '36 سم²', TRUE), (19, '40 سم²', FALSE), (19, '30 سم²', FALSE), (19, '50 سم²', FALSE),
(20, '8', TRUE), (20, '6', FALSE), (20, '10', FALSE), (20, '12', FALSE);

INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(21, '8', TRUE), (21, '6', FALSE), (21, '10', FALSE), (21, '5', FALSE),
(22, '8 كواكب', TRUE), (22, '9 كواكب', FALSE), (22, '7 كواكب', FALSE), (22, '6 كواكب', FALSE),
(23, 'القاهرة', TRUE), (23, 'الإسكندرية', FALSE), (23, 'الجيزة', FALSE), (23, 'طنطا', FALSE),
(24, 'الحوت الأزرق', TRUE), (24, 'الفيل', FALSE), (24, 'الزرافة', FALSE), (24, 'الأسد', FALSE),
(25, '7 أيام', TRUE), (25, '6 أيام', FALSE), (25, '8 أيام', FALSE), (25, '9 أيام', FALSE),
(26, 'جورج واشنطن', TRUE), (26, 'أبراهام لينكولن', FALSE), (26, 'توماس جيفرسون', FALSE), (26, 'جون آدامز', FALSE),
(27, 'نهر النيل', TRUE), (27, 'نهر الأمازون', FALSE), (27, 'نهر المسيسيبي', FALSE), (27, 'نهر اليانغتسي', FALSE),
(28, '7 قارات', TRUE), (28, '5 قارات', FALSE), (28, '6 قارات', FALSE), (28, '8 قارات', FALSE),
(29, 'عطارد', TRUE), (29, 'الزهرة', FALSE), (29, 'المريخ', FALSE), (29, 'الأرض', FALSE),
(30, '24 ساعة', TRUE), (30, '20 ساعة', FALSE), (30, '22 ساعة', FALSE), (30, '26 ساعة', FALSE);
INSERT INTO answers (question_id, answer_text, is_correct) VALUES
(31, 'عطارد', TRUE), (31, 'الزهرة', FALSE), (31, 'المريخ', FALSE), (31, 'الأرض', FALSE),
(32, 'باريس', TRUE), (32, 'برلين', FALSE), (32, 'لندن', FALSE), (32, 'روما', FALSE),
(33, 'جبل إيفرست', TRUE), (33, 'جبل كيليمانجارو', FALSE), (33, 'جبل فوجي', FALSE), (33, 'جبل الألب', FALSE),
(34, '11 لاعبًا', TRUE), (34, '10 لاعبين', FALSE), (34, '12 لاعبًا', FALSE), (34, '13 لاعبًا', FALSE),
(35, 'يوري غاغارين', TRUE), (35, 'نيل آرمسترونغ', FALSE), (35, 'سالي رايد', FALSE), (35, 'فالنتينا تيريشكوفا', FALSE),
(36, 'اليابانية', TRUE), (36, 'الصينية', FALSE), (36, 'الكورية', FALSE), (36, 'الإنجليزية', FALSE),
(37, 'البحر الأبيض المتوسط', TRUE), (37, 'البحر الأحمر', FALSE), (37, 'المحيط الأطلسي', FALSE), (37, 'المحيط الهادئ', FALSE),
(38, '52 أسبوعًا', TRUE), (38, '53 أسبوعًا', FALSE), (38, '50 أسبوعًا', FALSE), (38, '51 أسبوعًا', FALSE),
(39, 'إسحاق نيوتن', TRUE), (39, 'ألبرت أينشتاين', FALSE), (39, 'غاليليو غاليلي', FALSE), (39, 'نيكولا تسلا', FALSE),
(40, 'شلال أنجل', TRUE), (40, 'شلال نياجارا', FALSE), (40, 'شلال فيكتوريا', FALSE), (40, 'شلال كيبيل', FALSE);

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
-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Oct 20, 2025 at 02:50 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sihat_al_haj`
--

-- --------------------------------------------------------

--
-- Table structure for table `chronic_diseases`
--

CREATE TABLE `chronic_diseases` (
  `disease_id` int(11) NOT NULL,
  `name_ar` varchar(100) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `risk_level` enum('low','medium','high') DEFAULT 'medium'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chronic_diseases`
--

INSERT INTO `chronic_diseases` (`disease_id`, `name_ar`, `name_en`, `description_ar`, `description_en`, `risk_level`) VALUES
(0, 'ارتفاع ضغط الدم', 'Hypertension', 'مرض ضغط الدم المزمن شائع بين الحجاج كبار السن', 'Chronic high blood pressure, common among older pilgrims', 'medium'),
(0, 'السكري', 'Diabetes', 'مرض السكري من النوع الثاني يحتاج مراقبة للغذاء والأدوية أثناء الحج', 'Type 2 diabetes — requires monitoring of diet and meds during Hajj', 'high'),
(0, 'أمراض القلب', 'Heart Disease', 'أمراض القلب التاجية والحالات المزمنة التي تحتاج متابعة', 'Coronary and chronic heart conditions that need follow-up', 'high'),
(0, 'الربو', 'Asthma', 'اضطراب تنفسي قد يتفاقم بسبب الإجهاد والطقس', 'Respiratory condition that may worsen due to stress and heat', 'medium'),
(0, 'أمراض مفاصل', 'Arthritis', 'آلام المفاصل تؤثر على الحركة أثناء الطواف والسعي', 'Joint pain affecting mobility during Tawaf and Sa\'i', 'low');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `sender_name` varchar(255) NOT NULL,
  `sender_email` varchar(255) NOT NULL,
  `subject_ar` varchar(255) DEFAULT NULL,
  `subject_en` varchar(255) DEFAULT NULL,
  `message_content` text NOT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('new','in_progress','resolved') DEFAULT 'new',
  `resolved_by_user_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `countries`
--

CREATE TABLE `countries` (
  `country_id` int(11) NOT NULL,
  `name_ar` varchar(100) NOT NULL,
  `name_en` varchar(100) NOT NULL,
  `iso_code` varchar(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `countries`
--

INSERT INTO `countries` (`country_id`, `name_ar`, `name_en`, `iso_code`) VALUES
(1, 'المملكة العربية السعودية', 'Saudi Arabia', 'SA'),
(2, 'مصر', 'Egypt', 'EG'),
(3, 'العراق', 'Iraq', 'IQ'),
(4, 'الجزائر', 'Algeria', 'DZ'),
(5, 'المغرب', 'Morocco', 'MA'),
(6, 'السودان', 'Sudan', 'SD'),
(7, 'اليمن', 'Yemen', 'YE'),
(8, 'سوريا', 'Syria', 'SY'),
(9, 'تونس', 'Tunisia', 'TN'),
(10, 'الإمارات العربية المتحدة', 'United Arab Emirates', 'AE'),
(11, 'ليبيا', 'Libya', 'LY'),
(12, 'الأردن', 'Jordan', 'JO'),
(13, 'لبنان', 'Lebanon', 'LB'),
(14, 'عُمان', 'Oman', 'OM'),
(15, 'الكويت', 'Kuwait', 'KW'),
(16, 'قطر', 'Qatar', 'QA'),
(17, 'البحرين', 'Bahrain', 'BH'),
(18, 'موريتانيا', 'Mauritania', 'MR'),
(19, 'جيبوتي', 'Djibouti', 'DJ'),
(20, 'جزر القمر', 'Comoros', 'KM'),
(21, 'فلسطين', 'Palestine', 'PS'),
(22, 'الصومال', 'Somalia', 'SO');

-- --------------------------------------------------------

--
-- Table structure for table `emergency_responses`
--

CREATE TABLE `emergency_responses` (
  `response_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `responder_user_id` int(11) DEFAULT NULL,
  `medical_team_id` int(11) DEFAULT NULL,
  `response_details_ar` text DEFAULT NULL,
  `response_details_en` text DEFAULT NULL,
  `response_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `outcome_ar` text DEFAULT NULL,
  `outcome_en` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `question_ar` text NOT NULL,
  `question_en` text NOT NULL,
  `answer_ar` text NOT NULL,
  `answer_en` text NOT NULL,
  `category_ar` varchar(100) DEFAULT NULL,
  `category_en` varchar(100) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faqs`
--

INSERT INTO `faqs` (`faq_id`, `question_ar`, `question_en`, `answer_ar`, `answer_en`, `category_ar`, `category_en`, `display_order`, `is_active`) VALUES
(0, 'كيف أحصل على رعاية طبية أثناء الحج؟', 'How can I get medical care during Hajj?', 'توجد مراكز طوارئ ومستشفيات مخصصة في مكة والمدينة ومنطقة عرفات', 'Emergency centers and hospitals are available in Makkah, Madinah and Arafat', 'المعلومات الطبية', 'Medical Info', 1, 1),
(0, 'هل يجب أخذ لقاحات قبل الحج؟', 'Do I need vaccinations before Hajj?', 'يوصى بلقاحات مثل الحمى الشوكية والإنفلونزا حسب التوجيهات السعودية', 'Vaccinations like meningococcal and influenza are recommended per Saudi guidelines', 'التحصينات', 'Vaccinations', 2, 1),
(0, 'ما هي ساعات عمل المراكز الطبية في المشاعر؟', 'What are medical center hours in the holy sites?', 'تعمل العديد من المراكز على مدار 24 ساعة خلال موسم الحج', 'Many centers operate 24/7 during Hajj season', 'الخدمات', 'Services', 3, 1),
(0, 'كيف أتعامل مع الإرهاق والحرارة؟', 'How to deal with heat and exhaustion?', 'اشرب كميات كافية من الماء وتجنب التعرض للشمس لفترات طويلة', 'Drink fluids, avoid prolonged sun exposure', 'الصحة العامة', 'General Health', 4, 1),
(0, 'ماذا أفعل في حال فقدت مجموعتي؟', 'What to do if I lose my group?', 'تواصل مع مركز المرشدين أو قسم المفقودات في المسجد الحرام أو المدينة', 'Contact the guides center or lost & found at the Holy Mosque', 'التنظيم', 'Logistics', 5, 1);

-- --------------------------------------------------------

--
-- Table structure for table `hajj_packages`
--

CREATE TABLE `hajj_packages` (
  `package_id` int(11) NOT NULL,
  `package_name_ar` varchar(255) NOT NULL,
  `package_name_en` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `currency` varchar(10) DEFAULT 'SAR',
  `provider_id` int(11) DEFAULT NULL COMMENT 'Optional: if package is offered by a service provider'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hajj_timeline`
--

CREATE TABLE `hajj_timeline` (
  `ritual_id` int(11) NOT NULL,
  `ritual_name_ar` varchar(255) NOT NULL,
  `ritual_name_en` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `scheduled_date` date DEFAULT NULL,
  `scheduled_time` time DEFAULT NULL,
  `location_ar` varchar(255) DEFAULT NULL,
  `location_en` varchar(255) DEFAULT NULL,
  `display_order` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `hajj_timeline`
--

INSERT INTO `hajj_timeline` (`ritual_id`, `ritual_name_ar`, `ritual_name_en`, `description_ar`, `description_en`, `scheduled_date`, `scheduled_time`, `location_ar`, `location_en`, `display_order`) VALUES
(0, 'الإحرام من الميقات', 'Ihram from Miqat', 'الاستعداد والنية وارتداء ملابس الإحرام من الميقات المحدد', 'Make intention and don Ihram at the designated Miqat', '2025-06-15', '05:00:00', 'ميقات ذو الحليفة', 'Dhu al-Hulayfah', 1),
(0, 'الطواف حول الكعبة', 'Tawaf around Kaaba', 'الطواف حول الكعبة سبع مرات في المسجد الحرام', 'Circumambulation around the Kaaba seven times', '2025-06-16', '10:00:00', 'المسجد الحرام، مكة', 'Al-Masjid al-Haram, Makkah', 2),
(0, 'السعي بين الصفا والمروة', 'Sa\'i between Safa and Marwah', 'السعي بين الصفا والمروة سبع مرات بعد الطواف', 'Sa\'i between Safa and Marwah seven circuits', '2025-06-16', '11:00:00', 'الصفا والمروة، مكة', 'Safa and Marwah, Makkah', 3),
(0, 'الوقوف بعرفة', 'Standing at Arafat', 'الوقوف بعرفة في اليوم التاسع من ذي الحجة', 'Standing at Arafat on 9th Dhu al-Hijjah', '2025-06-19', '09:00:00', 'عرفات', 'Arafat', 4),
(0, 'مزدلفة والمبيت', 'Muzdalifah and Overnight', 'الانتقال إلى مزدلفة وجمع الحصى للمناورات', 'Move to Muzdalifah and collect pebbles for ritual', '2025-06-19', '20:00:00', 'مزدلفة', 'Muzdalifah', 5);

-- --------------------------------------------------------

--
-- Table structure for table `health_guidelines`
--

CREATE TABLE `health_guidelines` (
  `guideline_id` int(11) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `content_ar` text NOT NULL,
  `content_en` text NOT NULL,
  `category_ar` varchar(100) DEFAULT NULL,
  `category_en` varchar(100) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `health_guidelines`
--

INSERT INTO `health_guidelines` (`guideline_id`, `title_ar`, `title_en`, `content_ar`, `content_en`, `category_ar`, `category_en`, `image_url`, `created_at`, `updated_at`) VALUES
(0, 'تعليمات شرب الماء', 'Hydration Guidance', 'اشرب الماء بانتظام خاصة أثناء الطواف والسعي لتجنب الجفاف', 'Drink regularly to avoid dehydration during Tawaf and Sa\'i', 'توصيات', 'Recommendations', '/images/guidelines/hydration.png', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'التعامل مع الحرارة', 'Heat Management', 'ارتدِ قبعة واستخدم مظلات وابتعد عن الشمس في أوقات الذروة', 'Wear a hat, use shade and avoid peak sun hours', 'الوقاية', 'Prevention', '/images/guidelines/heat.png', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'إدارة الأدوية للسكري', 'Diabetes Medication Management', 'تأكد من تناول الأنسولين/الأدوية في مواعيدها واحمل نسخًا من الوصفات', 'Take insulin/meds on time and carry prescriptions', 'أمراض مزمنة', 'Chronic Conditions', '/images/guidelines/diabetes.png', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'نصائح تنفسية للربو', 'Asthma Care Tips', 'احمل جهاز الاستنشاق وابتعد عن المهيجات واطلب العناية عند ضيق التنفس', 'Carry inhaler, avoid triggers, seek care if short of breath', 'أمراض تنفسية', 'Respiratory', '/images/guidelines/asthma.png', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'أولى الإسعافات الحيوية', 'Basic First Aid', 'تعرف على نقاط الإسعاف واحفظ أرقام الطوارئ المحلية', 'Know first aid points and local emergency numbers', 'إسعاف', 'First Aid', '/images/guidelines/first_aid.png', '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `health_reports`
--

CREATE TABLE `health_reports` (
  `report_id` int(11) NOT NULL,
  `pilgrim_id` int(11) NOT NULL,
  `reporter_user_id` int(11) NOT NULL COMMENT 'User ID of the medical personnel creating the report',
  `report_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `diagnosis_ar` text DEFAULT NULL,
  `diagnosis_en` text DEFAULT NULL,
  `treatment_ar` text DEFAULT NULL,
  `treatment_en` text DEFAULT NULL,
  `medications_prescribed_ar` text DEFAULT NULL,
  `medications_prescribed_en` text DEFAULT NULL,
  `follow_up_notes_ar` text DEFAULT NULL,
  `follow_up_notes_en` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `accuracy_meters` decimal(10,2) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_centers`
--

CREATE TABLE `medical_centers` (
  `center_id` int(11) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `address_ar` varchar(255) DEFAULT NULL,
  `address_en` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `operating_hours_ar` varchar(255) DEFAULT NULL,
  `operating_hours_en` varchar(255) DEFAULT NULL,
  `services_offered_ar` text DEFAULT NULL,
  `services_offered_en` text DEFAULT NULL,
  `status` enum('active','inactive','full') DEFAULT 'active',
  `icon_name` varchar(50) DEFAULT 'hospital',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_centers`
--

INSERT INTO `medical_centers` (`center_id`, `name_ar`, `name_en`, `address_ar`, `address_en`, `latitude`, `longitude`, `phone_number`, `operating_hours_ar`, `operating_hours_en`, `services_offered_ar`, `services_offered_en`, `status`, `icon_name`, `created_at`, `updated_at`) VALUES
(0, 'مركز صحي الحرم', 'Al-Haram Medical Center', 'المسجد الحرام - مكة', 'Al-Masjid al-Haram - Makkah', 21.42248700, 39.82620600, '+966500000001', '24 ساعة', '24 Hours', 'عيادات طوارئ، إسعاف، صيدلية', 'Emergency clinics, ambulance, pharmacy', 'active', 'hospital', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مركز عرفات الطبي', 'Arafat Medical Center', 'منطقة عرفات', 'Arafat Area', 21.33500000, 39.98300000, '+966500000002', '24 ساعة', '24 Hours', 'طوارئ، أطباء باطني، إسعاف', 'Emergency, internal medicine, ambulance', 'active', 'clinic', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مركز منى الصحي', 'Mina Health Center', 'مناسك منى', 'Mina', 21.42200000, 39.88200000, '+966500000003', 'موسمي - موسم الحج', 'Seasonal - Hajj Season', 'خدمات إسعاف وخدمات أولية', 'Ambulance and primary care', 'active', 'clinic', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مركز المدينة الجامعي الطبي', 'Madinah University Medical Center', 'المدينة المنورة، قرب المسجد النبوي', 'Madinah near Al-Masjid an-Nabawi', 24.46700000, 39.61100000, '+966500000004', '08:00-22:00', '08:00-22:00', 'عيادات تخصصية، تحليل مختبر', 'Specialty clinics, lab tests', 'active', 'hospital', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مركز صحة الحجاج', 'Pilgrims Health Center', 'مركز المواصلات في مكة', 'Makkah transit center', 21.43000000, 39.82500000, '+966500000005', '24 ساعة', '24 Hours', 'اسعاف، رعاية أولية', 'Ambulance, primary care', 'active', 'hospital', '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `medical_requests`
--

CREATE TABLE `medical_requests` (
  `request_id` int(11) NOT NULL,
  `pilgrim_id` int(11) NOT NULL,
  `request_type_ar` varchar(100) NOT NULL COMMENT 'e.g., Emergency, Consultation, First Aid',
  `request_type_en` varchar(100) NOT NULL,
  `urgency_level` enum('low','medium','high','critical') DEFAULT 'medium',
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `current_latitude` decimal(10,8) DEFAULT NULL,
  `current_longitude` decimal(11,8) DEFAULT NULL,
  `status` enum('pending','in_progress','resolved','cancelled') DEFAULT 'pending',
  `assigned_team_id` int(11) DEFAULT NULL,
  `requested_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_teams`
--

CREATE TABLE `medical_teams` (
  `team_id` int(11) NOT NULL,
  `team_name_ar` varchar(255) NOT NULL,
  `team_name_en` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `current_location_ar` varchar(255) DEFAULT NULL,
  `current_location_en` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `status` enum('available','on_mission','on_break','unavailable') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medical_teams`
--

INSERT INTO `medical_teams` (`team_id`, `team_name_ar`, `team_name_en`, `description_ar`, `description_en`, `current_location_ar`, `current_location_en`, `contact_number`, `status`, `created_at`, `updated_at`) VALUES
(0, 'فريق الطوارئ الحرم', 'Al-Haram Emergency Team', 'فريق للاستجابة السريعة داخل نطاق المسجد الحرام', 'Fast response team within Al-Haram area', 'المسجد الحرام', 'Al-Haram', '+966550000001', 'available', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'فريق عرفات الطبي', 'Arafat Medical Team', 'يتواجد في عرفات لمباشرة الحالات الطارئة', 'Based in Arafat to handle emergencies', 'عرفات', 'Arafat', '+966550000002', 'on_mission', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'فريق منى المتنقل', 'Mina Mobile Team', 'فريق متنقل يغطي مشاعر منى', 'Mobile team covering Mina areas', 'منى', 'Mina', '+966550000003', 'available', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'فريق مزدلفة للمساعدة', 'Muzdalifah Assistance Team', 'يدير المبيت والخدمات الإسعافية بمزدلفة', 'Oversees overnight services and ambulance in Muzdalifah', 'مزدلفة', 'Muzdalifah', '+966550000004', 'available', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'فريق المدينة الجوال', 'Madinah Mobile Team', 'يغطي زوار المدينة ويمدهم بخدمات طبية', 'Covers visitors in Madinah with medical services', 'المدينة المنورة', 'Madinah', '+966550000005', 'on_break', '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `medical_team_members`
--

CREATE TABLE `medical_team_members` (
  `team_member_id` int(11) NOT NULL,
  `team_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL COMMENT 'User ID of the medical personnel',
  `role_in_team_ar` varchar(100) DEFAULT NULL,
  `role_in_team_en` varchar(100) DEFAULT NULL,
  `joined_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `template_id` int(11) DEFAULT NULL,
  `recipient_user_id` int(11) DEFAULT NULL,
  `recipient_group_id` int(11) DEFAULT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `content_ar` text NOT NULL,
  `content_en` text NOT NULL,
  `type` enum('email','sms','push','in-app') NOT NULL,
  `category` enum('general','emergency','health','itinerary','security','reminder','system','warning','information') DEFAULT 'general',
  `priority` enum('low','normal','high','urgent') DEFAULT 'normal',
  `icon_name` varchar(100) DEFAULT NULL,
  `action_url` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_templates`
--

CREATE TABLE `notification_templates` (
  `template_id` int(11) NOT NULL,
  `template_key` varchar(100) NOT NULL,
  `title_ar` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `content_ar` text NOT NULL,
  `content_en` text NOT NULL,
  `type` enum('email','sms','push','in-app') NOT NULL,
  `category` enum('general','emergency','health','itinerary','security','reminder','system','warning','information') DEFAULT 'general',
  `variables_json` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'JSON array of variable names, e.g., ["pilgrim_name", "time_remaining"]' CHECK (json_valid(`variables_json`)),
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification_templates`
--

INSERT INTO `notification_templates` (`template_id`, `template_key`, `title_ar`, `title_en`, `content_ar`, `content_en`, `type`, `category`, `variables_json`, `is_active`, `created_at`, `updated_at`) VALUES
(0, 'arrival_welcome', 'مرحبا بالحاج', 'Welcome to Hajj', 'مرحبا بك في منصة صحة الحج، تابع تعليماتنا وابق آمناً', 'Welcome to Sihat Al-Haj — follow our guidelines and stay safe', 'push', 'general', '[\"pilgrim_name\"]', 1, '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'heat_alert', 'تنبيه حرارة عالية', 'High Heat Alert', 'درجات الحرارة مرتفعة اليوم، تجنب التعرض المباشر للشمس', 'High temperatures today — avoid direct sun exposure', 'sms', 'health', '[\"location\"]', 1, '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'medical_assignment', 'تم تعيين فريق طبي', 'Medical Team Assigned', 'تم تعيين فريق طبي لمساعدتك عند الموقع {location}', 'A medical team has been assigned to assist you at {location}', 'in-app', 'emergency', '[\"team_name\",\"location\"]', 1, '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'daily_schedule', 'جدول يومي للحج', 'Daily Hajj Schedule', 'هذا جدولك لليوم: {schedule}', 'Your schedule for today: {schedule}', 'email', 'itinerary', '[\"schedule\"]', 1, '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'reminder_med', 'تذكير تناول الدواء', 'Medication Reminder', 'تذكير: تناول دوائك في الوقت المحدد', 'Reminder: take your medication on time', 'push', 'reminder', '[\"med_name\",\"time\"]', 1, '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `pilgrims`
--

CREATE TABLE `pilgrims` (
  `pilgrim_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) DEFAULT NULL,
  `qr_code_data` varchar(255) DEFAULT NULL COMMENT 'Data for QR code, linking to medical profile',
  `health_status_overview_ar` text DEFAULT NULL COMMENT 'General health status summary in Arabic',
  `health_status_overview_en` text DEFAULT NULL COMMENT 'General health status summary in English',
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_number` varchar(20) DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `allergies_ar` text DEFAULT NULL,
  `allergies_en` text DEFAULT NULL,
  `medications_ar` text DEFAULT NULL,
  `medications_en` text DEFAULT NULL,
  `medical_history_ar` text DEFAULT NULL,
  `medical_history_en` text DEFAULT NULL,
  `hajj_package_id` int(11) DEFAULT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pilgrims`
--

INSERT INTO `pilgrims` (`pilgrim_id`, `user_id`, `group_id`, `qr_code_data`, `health_status_overview_ar`, `health_status_overview_en`, `emergency_contact_name`, `emergency_contact_number`, `blood_type`, `allergies_ar`, `allergies_en`, `medications_ar`, `medications_en`, `medical_history_ar`, `medical_history_en`, `hajj_package_id`, `registration_date`) VALUES
(0, 0, NULL, 'QR_0_1760946899', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 07:54:59'),
(0, 0, NULL, 'QR_0_1760947303', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 08:01:43'),
(0, 0, NULL, 'QR_0_1760949547', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 08:39:07');

-- --------------------------------------------------------

--
-- Table structure for table `pilgrim_chronic_diseases`
--

CREATE TABLE `pilgrim_chronic_diseases` (
  `pilgrim_disease_id` int(11) NOT NULL,
  `pilgrim_id` int(11) NOT NULL,
  `disease_id` int(11) NOT NULL,
  `notes_ar` text DEFAULT NULL,
  `notes_en` text DEFAULT NULL,
  `diagnosed_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pilgrim_groups`
--

CREATE TABLE `pilgrim_groups` (
  `group_id` int(11) NOT NULL,
  `group_name_ar` varchar(255) NOT NULL,
  `group_name_en` varchar(255) NOT NULL,
  `leader_user_id` int(11) DEFAULT NULL COMMENT 'User ID of the group leader (could be a Guide)',
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pilgrim_groups`
--

INSERT INTO `pilgrim_groups` (`group_id`, `group_name_ar`, `group_name_en`, `leader_user_id`, `description_ar`, `description_en`, `created_at`, `updated_at`) VALUES
(0, 'مجموعة مكة مركز 1', 'Makkah Group 1', NULL, 'مجموعة حجاج من مكة بقيادة المرشد المحلي', 'Pilgrim group based in Makkah led by local guide', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مجموعة المدينة 2', 'Madinah Group 2', NULL, 'مجموعة زوار المدينة', 'Madinah visitors group', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مجموعة عرفات 3', 'Arafat Group 3', NULL, 'مجموعة مخصصة لوقوف عرفات', 'Group assigned for Arafat standing', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مجموعة منى 4', 'Mina Group 4', NULL, 'مجموعة تسهيل المهام في منى', 'Group to help logistics in Mina', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مجموعة مزدلفة 5', 'Muzdalifah Group 5', NULL, 'مجموعة للمبيت في مزدلفة', 'Overnight group in Muzdalifah', '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `pilgrim_health_data`
--

CREATE TABLE `pilgrim_health_data` (
  `health_data_id` int(11) NOT NULL,
  `pilgrim_id` int(11) NOT NULL,
  `measurement_type_ar` varchar(100) NOT NULL COMMENT 'e.g., Blood Pressure, Heart Rate, Temperature',
  `measurement_type_en` varchar(100) NOT NULL,
  `measurement_value` varchar(50) NOT NULL,
  `unit_ar` varchar(20) DEFAULT NULL,
  `unit_en` varchar(20) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `recorded_by_user_id` int(11) DEFAULT NULL COMMENT 'Optional: if recorded by medical personnel'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name_ar` varchar(100) NOT NULL,
  `role_name_en` varchar(100) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `service_id` int(11) NOT NULL,
  `service_name_ar` varchar(255) NOT NULL,
  `service_name_en` varchar(255) NOT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `icon_name` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`service_id`, `service_name_ar`, `service_name_en`, `description_ar`, `description_en`, `icon_name`, `is_active`, `created_at`, `updated_at`) VALUES
(0, 'نقل المواصلات', 'Transport Services', 'نقل بين أماكن السكن والمشاعر', 'Transport between accommodation and Hajj sites', 'bus', 1, '2025-10-20 07:39:56', '2025-10-20 07:39:56'),
(0, 'إقامة', 'Accommodation', 'حجز الإقامة في مكة والمدينة', 'Booking accommodation in Makkah and Madinah', 'hotel', 1, '2025-10-20 07:39:56', '2025-10-20 07:39:56'),
(0, 'الرعاية الصحية الطارئة', 'Emergency Medical Care', 'استجابة طبية للحالات الطارئة', 'Emergency medical response', 'ambulance', 1, '2025-10-20 07:39:56', '2025-10-20 07:39:56'),
(0, 'الإرشاد الديني', 'Religious Guidance', 'دروس وإرشاد أثناء الحج والعمرة', 'Religious guidance and lessons during Hajj & Umrah', 'book', 1, '2025-10-20 07:39:56', '2025-10-20 07:39:56'),
(0, 'المعلومات والخدمات', 'Information & Services', 'مراكز معلومات وخدمات للحجاج', 'Information desks and pilgrim services', 'info', 1, '2025-10-20 07:39:56', '2025-10-20 07:39:56');

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `provider_id` int(11) NOT NULL,
  `name_ar` varchar(255) NOT NULL,
  `name_en` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone_number` varchar(50) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `logo_url` varchar(255) DEFAULT NULL,
  `website_url` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `address_ar` text DEFAULT NULL,
  `address_en` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`provider_id`, `name_ar`, `name_en`, `email`, `phone_number`, `description_ar`, `description_en`, `logo_url`, `website_url`, `status`, `address_ar`, `address_en`, `created_at`, `updated_at`) VALUES
(0, 'مقدم خدمات السكن الحرم', 'Al-Haram Accommodation', 'haramstay@providers.sa', '+966560000001', 'تنظيم الإقامة القريبة من الحرم', 'Accommodation near Al-Haram', NULL, NULL, 'active', 'مكة - قرب الحرم', 'Makkah - near Al-Haram', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مزود النقل للمشاعر', 'Mashair Transport', 'mashair@providers.sa', '+966560000002', 'خدمات النقل بين المشاعر', 'Transport between Hajj sites', NULL, NULL, 'active', 'مكة - ميناء المواصلات', 'Makkah - transit hub', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'خدمات الإرشاد', 'Guidance Services', 'guides@providers.sa', '+966560000003', 'دليل ومرافقة المجموعات', 'Guides and group accompaniment', NULL, NULL, 'active', 'المدينة - مركز المرشدين', 'Madinah - guides center', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'مركز النبع الطبي', 'Al-Nabaa Medical Provider', 'nabaa@providers.sa', '+966560000004', 'توفير طواقم طبية ومستلزمات', 'Supply medical teams and equipment', NULL, NULL, 'active', 'مكة - حي طبي', 'Makkah - medical district', '2025-10-20 07:39:55', '2025-10-20 07:39:55'),
(0, 'خدمة الضيافة للحجاج', 'Pilgrim Hospitality', 'hospitality@providers.sa', '+966560000005', 'خدمات ضيافة ومعلومات للحجاج', 'Hospitality & info services for pilgrims', NULL, NULL, 'active', 'مكة - مركز الخدمات', 'Makkah - services hub', '2025-10-20 07:39:55', '2025-10-20 07:39:55');

-- --------------------------------------------------------

--
-- Table structure for table `system_content`
--

CREATE TABLE `system_content` (
  `content_id` int(11) NOT NULL,
  `key_name` varchar(100) NOT NULL COMMENT 'Unique identifier for the content block (e.g., about_hero_title, vision_statement)',
  `title_ar` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `description_ar` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `icon_name` varchar(50) DEFAULT NULL,
  `value_numeric` int(11) DEFAULT NULL,
  `value_text_ar` text DEFAULT NULL,
  `value_text_en` text DEFAULT NULL,
  `content_type` enum('hero','vision_value','statistic','benefit','plan','contact_info','guideline','faq') NOT NULL,
  `display_order` int(11) DEFAULT 0,
  `last_updated_by_user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone_number` varchar(20) DEFAULT NULL,
  `first_name_ar` varchar(100) NOT NULL,
  `first_name_en` varchar(100) NOT NULL,
  `last_name_ar` varchar(100) NOT NULL,
  `last_name_en` varchar(100) NOT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `nationality_id` int(11) DEFAULT NULL,
  `id_number` varchar(50) DEFAULT NULL COMMENT 'National ID or Iqama number',
  `passport_number` varchar(50) DEFAULT NULL,
  `user_type` enum('pilgrim','guide','medical_personnel','administrator') NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `email`, `phone_number`, `first_name_ar`, `first_name_en`, `last_name_ar`, `last_name_en`, `profile_image_url`, `date_of_birth`, `gender`, `nationality_id`, `id_number`, `passport_number`, `user_type`, `status`, `created_at`, `updated_at`) VALUES
(0, '_5555', '$2y$10$RQ9Ex3QqpIjqnSo8z6ctt.bB/r26XDdMgv5iXBqpMf9jsRcuxF9xC', 'admin@sihatalhaj.com', '+9661111111111', 'مدير', 'Manager', 'النظام', 'System', NULL, NULL, NULL, 1, '1111111111', NULL, 'administrator', 'active', '2025-10-20 08:39:07', '2025-10-20 12:50:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `fk_contact_messages_resolved_by_user` (`resolved_by_user_id`);

--
-- Indexes for table `countries`
--
ALTER TABLE `countries`
  ADD PRIMARY KEY (`country_id`),
  ADD UNIQUE KEY `iso_code` (`iso_code`);

--
-- Indexes for table `emergency_responses`
--
ALTER TABLE `emergency_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD UNIQUE KEY `request_id_unique` (`request_id`);

--
-- Indexes for table `hajj_packages`
--
ALTER TABLE `hajj_packages`
  ADD PRIMARY KEY (`package_id`),
  ADD KEY `fk_hajj_packages_provider` (`provider_id`);

--
-- Indexes for table `medical_requests`
--
ALTER TABLE `medical_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `emergency_responses`
--
ALTER TABLE `emergency_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

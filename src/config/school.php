<?php

return [

    'name' => 'Madana Mohana Colleges, Inc.',
    'short' => 'MMCi',
    'tagline' => 'The first step in the right direction.',
    'phone' => '+63 917 314 6499',
    'email' => 'registrar@madanamohanacolleges.com',
    'address' => 'Kibawe, Bukidnon, Philippines',
    'domain' => 'madanamohanacolleges.com',

    // 🔹 STATS
    'stats' => [
        ['value' => 30, 'suffix' => '+', 'label' => 'Years of Service'],
        ['value' => 15, 'suffix' => ':1', 'label' => 'Student-Teacher Ratio'],
        ['value' => 15, 'suffix' => '+', 'label' => 'Faculty'],
        ['value' => 5, 'suffix' => '', 'label' => 'Departments'],
    ],

    // 🔹 PROGRAMS
    'academic_programs' => [
        [
            'tab' => 'basic',
            'subtitle' => 'Early Learning',
            'title' => 'Preschool & Kinder',
            'copy' => 'Building strong foundations for young learners.',
        ],
        [
            'tab' => 'basic',
            'subtitle' => 'Primary Education',
            'title' => 'Elementary',
            'copy' => 'Developing literacy, numeracy, and values.',
        ],
        [
            'tab' => 'secondary',
            'subtitle' => 'Junior High',
            'title' => 'Junior High School',
            'copy' => 'Structured academic and character formation.',
        ],
        [
            'tab' => 'secondary',
            'subtitle' => 'Senior High',
            'title' => 'Senior High School',
            'copy' => 'Preparing students for college and careers.',
        ],
        [
            'tab' => 'tech-voc',
            'subtitle' => 'TESDA Programs',
            'title' => 'Technical Education',
            'copy' => 'TESDA-aligned programs for workforce readiness.',
        ],
    ],

    // 🔹 DEPARTMENTS
    'departments' => [
        [
            'name' => 'Basic Education',
            'copy' => 'Strong academic foundation for all learners.',
            'icon_svg' => '📘',
        ],
        [
            'name' => 'Senior High',
            'copy' => 'Career and college readiness programs.',
            'icon_svg' => '🎓',
        ],
        [
            'name' => 'Technical Education',
            'copy' => 'TESDA-aligned skills development.',
            'icon_svg' => '🛠️',
        ],
    ],

    // 🔹 ADMISSION STEPS
    'steps' => [
        ['title' => 'Submit Inquiry', 'copy' => 'Fill out the admission form.'],
        ['title' => 'Review', 'copy' => 'Registrar evaluates your application.'],
        ['title' => 'Approval', 'copy' => 'Receive acceptance notification.'],
        ['title' => 'Enrollment', 'copy' => 'Complete enrollment process.'],
    ],

    // 🔹 NEWS
    'news' => [
        [
            'date' => 'April 2026',
            'type' => 'Announcement',
            'title' => 'Enrollment is now open',
            'href' => '#',
        ],
        [
            'date' => 'March 2026',
            'type' => 'Event',
            'title' => 'School Orientation Schedule',
            'href' => '#',
        ],
    ],

    // 🔹 FAQ
    'faqs' => [
        [
            'q' => 'How do I apply?',
            'a' => 'Click Apply Now and complete the admission form.',
        ],
        [
            'q' => 'When will I get results?',
            'a' => 'Applications are reviewed within a few days.',
        ],
    ],
    'nav_items' => [
        [
            'label' => 'Home',
            'href' => 'public.home',
            'fragment' => 'home',
        ],
        [
            'label' => 'About',
            'href' => 'public.about',
            'children' => [
                [
                    'label' => 'Our Story',
                    'href' => 'public.about',
                ],
                [
                    'label' => 'Departments',
                    'href' => 'public.home',
                    'fragment' => 'departments',
                ],
                [
                    'label' => 'Campus Life',
                    'href' => 'public.home',
                    'fragment' => 'campus',
                ],
            ],
        ],
        [
            'label' => 'Academics',
            'href' => 'public.home',
            'fragment' => 'programs',
            'children' => [
                [
                    'label' => 'Preschool & Elementary',
                    'href' => 'public.home',
                    'fragment' => 'programs',
                ],
                [
                    'label' => 'Junior High School',
                    'href' => 'public.home',
                    'fragment' => 'programs',
                ],
                [
                    'label' => 'Senior High Tracks',
                    'href' => 'public.home',
                    'fragment' => 'programs',
                ],
                [
                    'label' => 'Learning Support',
                    'href' => 'public.home',
                    'fragment' => 'support',
                ],
            ],
        ],
        [
            'key' => 'tesda',
            'label' => 'TESDA',
            'href' => 'public.tesda',
        ],
        [
            'label' => 'Admissions',
            'href' => 'public.admission.create',
        ],
        [
            'label' => 'News',
            'href' => 'public.home',
            'fragment' => 'news',
        ],
        [
            'label' => 'Contact',
            'href' => 'public.home',
            'fragment' => 'contact',
        ],
    ],
    'story_values' => [
        [
            'title' => 'Spiritual Love',
            'copy' => 'We teach learners to grow in love for God, others, and the environment.',
        ],
        [
            'title' => 'Compassion',
            'copy' => 'We promote kindness, empathy, and meaningful service.',
        ],
        [
            'title' => 'Self-Discipline',
            'copy' => 'We help learners develop focus, responsibility, and good habits.',
        ],
        [
            'title' => 'Excellence',
            'copy' => 'We pursue quality instruction, strong character, and purposeful achievement.',
        ],
        [
            'title' => 'Service',
            'copy' => 'We guide learners toward helpfulness and community contribution.',
        ],
        [
            'title' => 'Wisdom',
            'copy' => 'We value thoughtful learning and principled decision-making.',
        ],
    ],
    'tesda_enabled' => false, // change to true to show tesda nav_item and page
    'tesda_programs' => [
        [
            'title' => 'Hilot (Wellness Massage) NC II – Training',
            'description' => 'Competency-based training program that equips learners with the knowledge and practical skills in therapeutic massage, wellness care, and proper client handling aligned with TESDA standards.',
        ],
        [
            'title' => 'Hilot (Wellness Massage) NC II – Assessment',
            'description' => 'National Certification assessment for individuals who want to validate their skills and earn TESDA NC II certification in Hilot Wellness Massage.',
        ],
    ],

    'tesda_success' => [
        [
            'name' => 'Former Trainee',
            'program' => 'Hilot (Wellness Massage) NC II – Training',
            'photo' => 'assets/images/tesda/success-trainee-1.jpg',
            'video_url' => 'https://www.youtube.com/embed/VIDEO_ID_HERE',
            'story' => 'After completing the training, now providing massage services locally and earning steady income.',
        ],
        [
            'name' => 'Certified Assessee',
            'program' => 'Hilot (Wellness Massage) NC II – Assessment',
            'photo' => 'assets/images/tesda/success-assessee-1.jpg',
            'video_url' => 'https://www.youtube.com/embed/VIDEO_ID_HERE',
            'story' => 'Successfully passed TESDA assessment and is now a certified massage therapist.',
        ],
    ],
];
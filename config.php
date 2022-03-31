<?php 
return [
    //navigation begins
    'nav' => [
        //staff navigation menu items
        'staff' => [
            //first level
            [
                'title' => 'Dashboard',
                'url' => '',
                'fa'=> 'dashboard'
            ],
            [
                'title'=> 'All Scores',
                'url' => 'scores',
                'fa' => 'check'
            ],
            [
                'title' => 'Assignment',
                'url' => 'assignment',
                'fa'=> 'pencil'
            ],
            [
                'title' => 'Test',
                'url' => 'test',
                'fa'=> 'pencil'
            ],
            [
                'title' => 'Project',
                'url' => 'project',
                'fa'=> 'gears'
            ],
            [
                'title' => 'Exam',
                'url' => 'exam',
                'fa'=> 'pencil'
            ],
            [
                'title' => 'Psycho Motor',
                'url' => 'psycho-motor',
                'fa'=> 'twitter'
            ],
            [
                'title' => 'View Class Result',
                'url' => 'class-result',
                'fa'=> 'eye'
            ],
            [
                'title' => 'Teacher Comment',
                'url' => 'teacher-comment',
                'fa'=> 'comment'
            ],
            [
                'title' => 'Housemaster Comment',
                'url' => 'housemaster-comment',
                'fa'=> 'comment'
            ],
            [
                'title' => 'Profile Settings',
                'url' => 'profile',
                'fa'=> 'user'
            ]
        ],
        //admin navigation menu items
        'admin' => [
            [
                'title' => 'Dashboard',
                'url' => 'index.php',
                'fa'=> 'dashboard'
            ],
            [
                'title' => 'Exam Management',
                'url' => '#',
                'fa'=> 'book',
                'secondlevel' => [
                    [
                        'title' => 'Examination',
                        'url' => 'exam.php',
                        'fa' => 'pencil'
                    ],
                    [
                        'title' => 'Paper',
                        'url' => 'paper.php',
                        'fa' => 'pencil'
                    ],
                    [
                        'title' => 'Question',
                        'url' => 'question.php',
                        'fa' => 'pencil'
                    ],
                ]
            ],
            [
                'title' => 'Reports',
                'url' => '#',
                'fa' => 'book',
                'secondlevel' => [
                        [
                            'title' => 'Student Result',
                            'url' => 'student-result.php',
                            'fa' => 'user'
                        ]
                    ]
                ]
        ],
        'student' => [
            [
                'title' => 'Dashboard',
                'url' => 'dashboard.php',
                'fa'=> 'dashboard'
            ],
            [
                'title' => 'My Exams',
                'url' => '#',
                'fa'=> 'book',
                'secondlevel' => [
                    [
                        'title' => 'Pending Exams',
                        'url' => 'pendingexam.php',
                        'fa' => 'pencil'
                    ],
                    [
                        'title' => 'Exam Results',
                        'url' => 'result.php',
                        'fa' => 'pencil'
                    ]
                ]
            ],
            [
                'title' => 'Profile',
                'url' => 'profile.php',
                'fa' => 'user'
            ]
        ],
    ],
    //navigation ends
    'urlmode' => 'normal', //normal and pretty,
    // 'apibaseurl' => 'http://192.168.1.101/api/v1/',
    // 'apibaseurl' => 'http://192.168.1.101/e/api/v1/',
    // 'baseurl' => 'http://192.168.1.101/e/',
    'apibaseurl' => 'http://localhost/cbt/api/v1/',
    'baseurl' => 'http://localhost/cbt/',
    // 'apibaseurl' => 'http://192.168.1.101/cbt/api/v1/',
    // 'baseurl' => 'http://192.168.1.101/cbt/',
    'imagebase' => 'images/'
];
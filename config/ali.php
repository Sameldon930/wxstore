<?php

return [
    'use_sandbox' => false,
    //'partner' => '2088702361851285',
    'app_id' => '2018060760293872',
    'sign_type' => 'RSA2',
    'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAjOhv5GpgRIayMVhdlN7HAcOFku57V5rmP5Aj76IJexMfMvjUe3HxYF3XnkwFvNaYJ0zlRSUIKjkmycJBg+P2GDAhpD5zFq4+MGD5eICs97kKBj79B5176H7b+ipDfVNBP0Be8z9vLJhF7XN6IK4JOBFMPJ0IbYJz7kbS+zu7dwGSVRH9++y+ovTMFXsxEdSFnLwbDkELyug3PKw5wpsQSAbEO9A+DTUx8tO9Ja+dXANiRvlCVlfSMHNM4wVFADVkpkVcqY/5DO6J7Dwq1ekx0fQF5OQ46o9+eU4Zho1uDlKpS56Br/iFxfYbSNqeKBH58bcwgcWRqlxysurf1Ug5iQIDAQAB',
    'rsa_private_key' => 'MIIEpAIBAAKCAQEA5vi/3gnpcq15AmPRPrgSZapoJm4STUmd7fKevdIFUcZ17ogaJQEIpELV+UNhFK8oAxG57PjHE8p2B47fsvmqdJo5GJtELbLQ7Ur4a0PLrl7oWXHo777J/azSS6J2ILgquVX1DjBKeKRYP/1ZyxtAVKUtKfOiGJJAoijIPldpPzL3W9ky+BvE6OpG9c1XTXowO6x5VUbrSscYoAC7mKc/2oRhSMCMqjpA1XzdYHdMsT28PYjMPdUirgQe4mZGfUxPiaJr5Vf1DhsZimJ14DqpD2uue7Bu6a6xgmBBLaTrhf+LUsVMUuyKGw2jjYEzrb4OeaKNGrnb0GSs4YD8dFvDTQIDAQABAoIBAQCVdjhqjY10eQ8KXd9kr6awBUKaQ7YyL0Y0WUEh4oswrLVZ4tbQRUf7S4i9YT2sLeA9hDYFQ8NHakdQgsL9RRRpmfcClk7bJ5CNTWtNtjRSpPXEE7NBmPuK3U/EQ+AfMi3retU2FkfKo8a7uc1mYj8Rx0VflVm6fsG2bZ+Ov+/KRY0YWkNm9ChcydnnnSZg/AjMgVkL9cs32F1MZqai8FHxSAPlMfJlPpHvpmjjxdJsk8e8QcWLWG+Fcmy5hLMsbq50SUs3fqhxyvAS2aTqm85QIimDpxbEkZmJULajjxOaeHi1rHkXFrr1icpLDhA2L4f04mibMKIDJUHx9veDmr0BAoGBAPPv39sh3Gln9xqYftI4J9QVxjAny8M75gU3oZilTe+Im1lvOhIGTcDsZos8RtsIY5exVyXwfsKzunwjnFP8ddBN1gDWYBeH8NG/oLo1gccbKg12t71HIrAFfxqKVza9flgTi1GeMBRb/5QEyS4osUAVFdYtWbFbD24vUxkddsyNAoGBAPJkvXsnptgxaxbdqNZbeAdbU8u+i0jpIsHyeVQW/QXqwnTnLLguSqlFXUg8BFD5zkd8hS7rHk2XYv5r1uEUzehmoPDMIiOUmJRPt6jeKqJRdNOHDayPc40vF5yyfjO7i0F9/IR/K3U+YJkft/WniCRnWS82Sd2wI3Snj8jMuwHBAoGALcCb9/nN4WdCjZZ0kvE9ZyX+WiHcse2WIvxgLsUybO7uVEPsXF0aUEkGoq7Xi1VzeIwmkFMfM12KaKY1N/tuDXfL37RVqZQxsuWCO+q1QKbzqDrQE4w4EVO2lz/DQ90eyV5BDzngQrFOqnCLW0D+ncpWoik3XXB64Gu35koEcjECgYAyQKZz6OWTPrOX+v5s6mu5Arsdq3RO+l6FXRLAVz+/wUrtV5wS1y4NZ98OAbtXWmLkuTEXaBFRPsh8PLlA0sJZoyVMptWRENaNMPW/oceu/To+PGqeUuM+6vt3Nh4p9YDKZCc9BOxqkzMNq+DEoiX8fhykSBoKRuEi5zZNB/zhQQKBgQDIl6OtvOIit/RpXe1Z1/f9AdDLA1bVk+3Hg7j/+a01r1cBBwQ4VwtSUiNP1rrEa+F5PSBt86LzD5UI97Y05Jl2murYjQ8BBQJ02WK6x5nHGgnvHPb26tuY5SkCkyPj+bFu2GqM8paLOerP1H7V70u/UtPQWfEfh/lXlOe3B5mOPQ==',
    'limit_pay' => '',
    'notify_url' => 'http://mrf.huanhe.pro/api/aliPayCallback',
    'return_url' => 'http://www.baidu.com',
    'return_raw' => true,
    //'app_auth_token' => '201806BB2859d2cdc90142bd9a3e0a0789230F28',


    'debug'  => true,


    /*
     * 日志配置
     *
     * level: 日志级别，可选为：
     *                 debug/info/notice/warning/error/critical/alert/emergency
     * file：日志文件位置(绝对路径!!!)，要求可写权限
     */
    'log' => [
        'level' => env('ALI_LOG_LEVEL', 'debug'),
        'file'  => env('ALI_LOG_FILE', storage_path('logs/ali.log')),
    ],
];

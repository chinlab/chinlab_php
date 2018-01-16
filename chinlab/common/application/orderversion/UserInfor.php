<?php
namespace app\common\application\orderversion;

class UserInfor {
    /**
     * @var array
     *
     * 国家查询
     */
    static $country = [

        'c1' => [
            'zh' => '阿富汗',
            'en' => 'Afghanistan'
        ],
        'c2' => [
            'zh' => '阿尔巴尼亚',
            'en' => 'Albania'
        ],
        'c3' => [
            'zh' => '阿尔及利亚',
            'en' => 'Algeria'
        ],
        'c4' => [
            'zh' => '东萨摩亚',
            'en' => 'American Samoa'
        ],
        'c5' => [
            'zh' => '安道尔',
            'en' => 'Andorra'
        ],
        'c6' => [
            'zh' => '安哥拉',
            'en' => 'Angola'
        ],
        'c7' => [
            'zh' => '安圭拉岛',
            'en' => 'Anguilla'
        ],
        'c8' => [
            'zh' => '安提瓜和巴布达',
            'en' => 'Antigua and Barbuda'
        ],
        'c9' => [
            'zh' => '阿根廷',
            'en' => 'Argentina'
        ],
        'c10' => [
            'zh' => '亚美尼亚',
            'en' => 'Armenia'
        ],
        'c11' => [
            'zh' => '阿鲁巴',
            'en' => 'Aruba'
        ],
        'c12' => [
            'zh' => '澳大利亚',
            'en' => 'Australia'
        ],
        'c13' => [
            'zh' => '奥地利',
            'en' => 'Austria'
        ],
        'c14' => [
            'zh' => '阿塞拜疆',
            'en' => 'Azerbaijan'
        ],
        'c15' => [
            'zh' => '巴哈马',
            'en' => 'Bahamas'
        ],
        'c16' => [
            'zh' => '巴林',
            'en' => 'Bahrain'
        ],
        'c17' => [
            'zh' => '巴巴多斯',
            'en' => 'Barbados'
        ],
        'c18' => [
            'zh' => '孟加拉',
            'en' => 'Bangladesh'
        ],
        'c19' => [
            'zh' => '白俄罗斯',
            'en' => 'Belarus'
        ],
        'c20' => [
            'zh' => '比利时',
            'en' => 'Belgium'
        ],
        'c21' => [
            'zh' => '伯里兹',
            'en' => 'Belize'
        ],
        'c22' => [
            'zh' => '贝宁',
            'en' => 'Benin'
        ],
        'c23' => [
            'zh' => '百慕大',
            'en' => 'Bermuda'
        ],
        'c24' => [
            'zh' => '巴哈马',
            'en' => 'Bahamas'
        ],
        'c25' => [
            'zh' => '不丹',
            'en' => 'Bhutan'
        ],
        'c26' => [
            'zh' => '博茨瓦纳',
            'en' => 'Botswana'
        ],
        'c27' => [
            'zh' => '玻利维亚',
            'en' => 'Bolivia'
        ],
        'c28' => [
            'zh' => '波黑',
            'en' => 'Bosnia and Herzegovina'
        ],
        'c29' => [
            'zh' => '布韦岛',
            'en' => 'Bouvet Island'
        ],
        'c30' => [
            'zh' => '巴西',
            'en' => 'Brazil'
        ],
        'c31' => [
            'zh' => '文莱布鲁萨兰',
            'en' => 'Brunei Darussalam'
        ],
        'c32' => [
            'zh' => '保加利亚',
            'en' => 'Bulgaria'
        ],
        'c33' => [
            'zh' => '布基纳法索',
            'en' => 'Burkina Faso'
        ],
        'c34' => [
            'zh' => '布隆迪',
            'en' => 'Belize'
        ],
        'c35' => [
            'zh' => '柬埔寨',
            'en' => 'Cambodia'
        ],
        'c36' => [
            'zh' => '喀麦隆',
            'en' => 'Cameroon'
        ],
        'c37' => [
            'zh' => '加拿大',
            'en' => 'Canada'
        ],
        'c38' => [
            'zh' => '佛得角',
            'en' => 'Cape Verde'
        ],
        'c39' => [
            'zh' => '开曼群岛',
            'en' => 'Cayman Islands'
        ],
        'c40' => [
            'zh' => '中非',
            'en' => 'Central African Republic'
        ],
        'c41' => [
            'zh' => '乍得',
            'en' => 'Chad'
        ],
        'c42' => [
            'zh' => '智利',
            'en' => 'Chile'
        ],
        'c43' => [
            'zh' => '中国',
            'en' => 'China'
        ],
        'c44' => [
            'zh' => '哥伦比亚',
            'en' => 'Colombia'
        ],
        'c45' => [
            'zh' => '科摩罗',
            'en' => 'Comoros'
        ],
        'c46' => [
            'zh' => '刚果',
            'en' => 'Congo'
        ],
        'c47' => [
            'zh' => '哥斯达黎加',
            'en' => 'Costa Rica'
        ],
        'c48' => [
            'zh' => '克罗地亚',
            'en' => 'Croatia'
        ],
        'c49' => [
            'zh' => '古巴',
            'en' => 'Cuba'
        ],
        'c50' => [
            'zh' => '塞普路斯',
            'en' => 'Cyprus'
        ],
        'c51' => [
            'zh' => '捷克',
            'en' => 'Czech Republic'
        ],
        'c52' => [
            'zh' => '捷克斯洛伐克',
            'en' => 'Czechoslovakia'
        ],
        'c53' => [
            'zh' => '丹麦',
            'en' => 'Denmark'
        ],
        'c54' => [
            'zh' => '吉布提',
            'en' => 'Djibouti'
        ],
        'c55' => [
            'zh' => '多米尼加共和国',
            'en' => 'Dominica'
        ],
        'c56' => [
            'zh' => '多米尼加联邦',
            'en' => 'Dominican Republic'
        ],
        'c57' => [
            'zh' => '东帝汶',
            'en' => 'East Timor'
        ],
        'c58' => [
            'zh' => '厄瓜多尔',
            'en' => 'Ecuador'
        ],
        'c59' => [
            'zh' => '埃及',
            'en' => 'Egypt'
        ],
        'c60' => [
            'zh' => '萨尔瓦多',
            'en' => 'El Salvador'
        ],
        'c61' => [
            'zh' => '赤道几内亚',
            'en' => 'Equatorial Guinea'
        ],
        'c62' => [
            'zh' => '厄立特里亚',
            'en' => 'Eritrea'
        ],
        'c63' => [
            'zh' => '爱沙尼亚',
            'en' => 'Estonia'
        ],
        'c64' => [
            'zh' => '埃塞俄比亚',
            'en' => 'Ethiopia'
        ],
        'c65' => [
            'zh' => '福兰克群岛',
            'en' => 'Falkland Islands'
        ],
        'c66' => [
            'zh' => '法罗群岛',
            'en' => 'Faroe Islands'
        ],
        'c67' => [
            'zh' => '斐济',
            'en' => 'Fiji'
        ],
        'c68' => [
            'zh' => '芬兰',
            'en' => 'Finland'
        ],
        'c69' => [
            'zh' => '法国',
            'en' => 'France'
        ],
        'c70' => [
            'zh' => '法属圭亚那',
            'en' => 'French Guiana '
        ],
        'c71' => [
            'zh' => '法属玻里尼西亚',
            'en' => 'French Polynesia'
        ],
        'c72' => [
            'zh' => '加蓬',
            'en' => 'Gabon'
        ],
        'c73' => [
            'zh' => '冈比亚',
            'en' => 'Gambia'
        ],
        'c74' => [
            'zh' => '格鲁吉亚',
            'en' => 'Georgia'
        ],
        'c75' => [
            'zh' => '德国',
            'en' => 'Germany'
        ],
        'c76' => [
            'zh' => '加纳',
            'en' => 'Ghana'
        ],
        'c77' => [
            'zh' => '直布罗陀',
            'en' => 'Gibraltar'
        ],
        'c78' => [
            'zh' => '英国',
            'en' => 'Great Britain'
        ],
        'c79' => [
            'zh' => '希腊',
            'en' => 'Greece'
        ],
        'c80' => [
            'zh' => '格陵兰岛',
            'en' => 'Greenland'
        ],
        'c81' => [
            'zh' => '格林纳达',
            'en' => 'Grenada'
        ],
        'c82' => [
            'zh' => '危地马拉',
            'en' => 'Guatemala'
        ],
        'c83' => [
            'zh' => '几内亚',
            'en' => 'Guinea'
        ],
        'c84' => [
            'zh' => '几内亚比绍',
            'en' => 'Guinea-Bissau'
        ],
        'c85' => [
            'zh' => '圭亚那',
            'en' => 'Guyana'
        ],
        'c86' => [
            'zh' => '海地',
            'en' => 'Haiti'
        ],
        'c87' => [
            'zh' => '洪都拉斯',
            'en' => 'Honduras'
        ],
        'c88' => [
            'zh' => '匈牙利',
            'en' => 'Hungary'
        ],
        'c89' => [
            'zh' => '冰岛',
            'en' => 'Iceland'
        ],
        'c90' => [
            'zh' => '印度',
            'en' => 'India'
        ],
        'c91' => [
            'zh' => '印度尼西亚',
            'en' => 'Indonesia'
        ],
        'c92' => [
            'zh' => '伊朗',
            'en' => 'Iran'
        ],
        'c93' => [
            'zh' => '伊拉克',
            'en' => 'Iraq'
        ],
        'c94' => [
            'zh' => '爱尔兰',
            'en' => 'Ireland'
        ],
        'c95' => [
            'zh' => '以色列',
            'en' => 'Israel'
        ],
        'c96' => [
            'zh' => '意大利',
            'en' => 'Italy'
        ],
        'c97' => [
            'zh' => '牙买加',
            'en' => 'Jamaica'
        ],
        'c98' => [
            'zh' => '日本',
            'en' => 'Japan'
        ],
        'c99' => [
            'zh' => '约旦',
            'en' => 'Jordan'
        ],
        'c100' => [
            'zh' => '哈萨克斯坦',
            'en' => 'Kazakhstan'
        ],
        'c101' => [
            'zh' => '肯尼亚',
            'en' => 'Kenya'
        ],
        'c102' => [
            'zh' => '基里巴斯',
            'en' => 'Kiribati'
        ],
        'c103' => [
            'zh' => '朝鲜',
            'en' => 'Korea (North)'
        ],
        'c104' => [
            'zh' => '韩国',
            'en' => 'Korea (South)'
        ],
        'c105' => [
            'zh' => '科威特',
            'en' => 'Kuwait'
        ],
        'c106' => [
            'zh' => '吉尔吉斯斯坦',
            'en' => 'Kyrgyzstan'
        ],
        'c107' => [
            'zh' => '老挝',
            'en' => 'Laos'
        ],
        'c108' => [
            'zh' => '拉托维亚',
            'en' => 'Latvia'
        ],
        'c109' => [
            'zh' => '黎巴嫩',
            'en' => 'Lebanon'
        ],
        'c110' => [
            'zh' => '列支顿士登',
            'en' => 'Liechtenstein'
        ],
        'c111' => [
            'zh' => '利比里亚',
            'en' => 'Liberia'
        ],
        'c112' => [
            'zh' => '利比亚',
            'en' => 'Libya'
        ],
        'c113' => [
            'zh' => '莱索托',
            'en' => 'Lesotho'
        ],
        'c114' => [
            'zh' => '立陶宛',
            'en' => 'Lithuania'
        ],
        'c115' => [
            'zh' => '卢森堡',
            'en' => 'Luxembourg'
        ],
        'c116' => [
            'zh' => '马达加斯加',
            'en' => 'Madagascar'
        ],
        'c117' => [
            'zh' => '马拉维',
            'en' => 'Malawi'
        ],
        'c118' => [
            'zh' => '马来西亚',
            'en' => 'Malaysia'
        ],
        'c119' => [
            'zh' => '马尔代夫',
            'en' => 'Maldives'
        ],
        'c120' => [
            'zh' => '马里',
            'en' => 'Mali'
        ],
        'c121' => [
            'zh' => '马耳他',
            'en' => 'Malta'
        ],
        'c122' => [
            'zh' => '马绍尔群岛',
            'en' => 'Marshall Islands'
        ],
        'c123' => [
            'zh' => '毛里塔尼亚',
            'en' => 'Mauritania'
        ],
        'c124' => [
            'zh' => '毛里求斯',
            'en' => 'Mauritius'
        ],
        'c125' => [
            'zh' => '墨西哥',
            'en' => 'Mexico'
        ],
        'c126' => [
            'zh' => '米克罗尼西亚',
            'en' => 'Micronesia'
        ],
        'c127' => [
            'zh' => '摩纳哥',
            'en' => 'Monaco'
        ],
        'c128' => [
            'zh' => '摩尔多瓦',
            'en' => 'Moldova'
        ],
        'c129' => [
            'zh' => '摩洛哥',
            'en' => 'Morocco'
        ],
        'c130' => [
            'zh' => '蒙古',
            'en' => 'Mongolia'
        ],
        'c131' => [
            'zh' => '蒙塞拉特岛',
            'en' => 'Montserrat'
        ],
        'c132' => [
            'zh' => '莫桑比克',
            'en' => 'Mozambique'
        ],
        'c133' => [
            'zh' => '缅甸',
            'en' => 'Myanmar'
        ],
        'c134' => [
            'zh' => '纳米比亚',
            'en' => 'Namibia'
        ],
        'c135' => [
            'zh' => '瑙鲁',
            'en' => 'Nauru'
        ],
        'c136' => [
            'zh' => '尼泊尔',
            'en' => 'Nepal'
        ],
        'c137' => [
            'zh' => '荷兰',
            'en' => 'Netherlands'
        ],
        'c138' => [
            'zh' => '荷属安德列斯',
            'en' => 'Netherlands Antilles'
        ],
        'c139' => [
            'zh' => '新卡里多尼亚',
            'en' => 'New Caledonia'
        ],
        'c140' => [
            'zh' => '新西兰',
            'en' => 'New Zealand'
        ],
        'c141' => [
            'zh' => '尼加拉瓜',
            'en' => 'Nicaragua'
        ],
        'c142' => [
            'zh' => '尼日尔',
            'en' => 'Niger'
        ],
        'c143' => [
            'zh' => '尼日利亚',
            'en' => 'Nigeria'
        ],
        'c144' => [
            'zh' => '纽爱',
            'en' => 'Niue'
        ],
        'c145' => [
            'zh' => '诺福克岛',
            'en' => 'Norfolk Island'
        ],
        'c146' => [
            'zh' => '北马里亚纳群岛',
            'en' => 'Northern Mariana Islands'
        ],
        'c147' => [
            'zh' => '挪威',
            'en' => 'Norway'
        ],
        'c148' => [
            'zh' => '阿曼',
            'en' => 'Oman'
        ],
        'c149' => [
            'zh' => '巴基斯坦',
            'en' => 'Pakistan'
        ],
        'c150' => [
            'zh' => '帕劳',
            'en' => 'Palau'
        ],
        'c151' => [
            'zh' => '巴拿马',
            'en' => 'Panama'
        ],
        'c152' => [
            'zh' => '巴布亚新几内亚',
            'en' => 'Papua New Guinea'
        ],
        'c153' => [
            'zh' => '巴拉圭',
            'en' => 'Paraguay'
        ],
        'c154' => [
            'zh' => '秘鲁',
            'en' => 'Peru'
        ],
        'c155' => [
            'zh' => '菲律宾',
            'en' => 'Philippines'
        ],
        'c156' => [
            'zh' => '皮特克恩岛',
            'en' => 'Pitcairn'
        ],
        'c157' => [
            'zh' => '波兰',
            'en' => 'Poland'
        ],
        'c158' => [
            'zh' => '葡萄牙',
            'en' => 'Portugal'
        ],
        'c159' => [
            'zh' => '波多黎各',
            'en' => 'Puerto Rico'
        ],
        'c160' => [
            'zh' => '卡塔尔',
            'en' => 'Qatar'
        ],
        'c161' => [
            'zh' => '法属尼留旺岛',
            'en' => 'Reunion'
        ],
        'c162' => [
            'zh' => '罗马尼亚',
            'en' => 'Romania'
        ],
        'c163' => [
            'zh' => '俄罗斯',
            'en' => 'Russian Federation'
        ],
        'c164' => [
            'zh' => '卢旺达',
            'en' => 'Rwanda'
        ],
        'c165' => [
            'zh' => '圣基茨和尼维斯',
            'en' => 'Saint Kitts and Nevis'
        ],
        'c166' => [
            'zh' => '圣卢西亚',
            'en' => 'Saint Lucia'
        ],
        'c167' => [
            'zh' => '圣文森特和格陵纳丁斯',
            'en' => 'Saint Vincent and the Grenadines'
        ],
        'c168' => [
            'zh' => '西萨摩亚',
            'en' => 'Samoa'
        ],
        'c169' => [
            'zh' => '圣马力诺',
            'en' => 'San Marino'
        ],
        'c170' => [
            'zh' => '圣多美和普林西比',
            'en' => 'Sao Tome and Principe'
        ],
        'c171' => [
            'zh' => '沙特阿拉伯',
            'en' => 'Saudi Arabia'
        ],
        'c172' => [
            'zh' => '塞内加尔',
            'en' => 'Senegal'
        ],
        'c173' => [
            'zh' => '塞舌尔',
            'en' => 'Seychelles'
        ],
        'c174' => [
            'zh' => '吉布提',
            'en' => 'Sierra Leone'
        ],
        'c175' => [
            'zh' => '新加坡',
            'en' => 'Singapore'
        ],
        'c176' => [
            'zh' => '斯罗文尼亚',
            'en' => 'Slovenia'
        ],
        'c177' => [
            'zh' => '斯洛伐克',
            'en' => 'Slovak Republic'
        ],
        'c178' => [
            'zh' => '所罗门群岛',
            'en' => 'Solomon Islands'
        ],
        'c179' => [
            'zh' => '索马里',
            'en' => 'Somalia'
        ],
        'c180' => [
            'zh' => '南非',
            'en' => 'South Africa'
        ],
        'c181' => [
            'zh' => '斯里兰卡',
            'en' => 'Sri Lanka'
        ],
        'c182' => [
            'zh' => '圣赫勒拿',
            'en' => 'Saint?Helena'
        ],
        'c183' => [
            'zh' => '圣皮艾尔和密克隆群岛',
            'en' => 'Saint Pierre and Miquelon'
        ],
        'c184' => [
            'zh' => '苏丹',
            'en' => 'Sudan'
        ],
        'c185' => [
            'zh' => '苏里南',
            'en' => 'Suriname'
        ],
        'c186' => [
            'zh' => '斯瓦尔巴特和扬马延岛',
            'en' => 'Svalbard and Jan Mayen Islands'
        ],
        'c187' => [
            'zh' => '斯威士兰',
            'en' => 'Swaziland'
        ],
        'c188' => [
            'zh' => '瑞典',
            'en' => 'Sweden'
        ],
        'c189' => [
            'zh' => '瑞士',
            'en' => 'Switzerland'
        ],
        'c190' => [
            'zh' => '叙利亚',
            'en' => 'Syria'
        ],
        'c191' => [
            'zh' => '塔吉克斯坦',
            'en' => 'Tajikistan'
        ],
        'c192' => [
            'zh' => '坦桑尼亚',
            'en' => 'Tanzania'
        ],
        'c193' => [
            'zh' => '泰国',
            'en' => 'Thailand'
        ],
        'c194' => [
            'zh' => '多哥',
            'en' => 'Togo'
        ],
        'c195' => [
            'zh' => '托克劳群岛',
            'en' => 'Tokelau'
        ],
        'c196' => [
            'zh' => '汤加',
            'en' => 'Tonga'
        ],
        'c197' => [
            'zh' => '特立尼达和多巴哥',
            'en' => 'Trinidad and Tobago'
        ],
        'c198' => [
            'zh' => '突尼斯',
            'en' => 'Tunisia'
        ],
        'c199' => [
            'zh' => '土尔其',
            'en' => 'Turkey'
        ],
        'c200' => [
            'zh' => '土库曼斯坦',
            'en' => 'Turkmenistan'
        ],
        'c201' => [
            'zh' => '特克斯和凯科斯群岛',
            'en' => 'Turks and Caicos Islands'
        ],
        'c202' => [
            'zh' => '图瓦卢',
            'en' => 'Tuvalu'
        ],
        'c203' => [
            'zh' => '乌干达',
            'en' => 'Uganda'
        ],
        'c204' => [
            'zh' => '乌克兰',
            'en' => 'Ukraine'
        ],
        'c205' => [
            'zh' => '阿联酋',
            'en' => 'United Arab Emirates'
        ],
        'c206' => [
            'zh' => '英国',
            'en' => 'United Kingdom'
        ],
        'c207' => [
            'zh' => '美国',
            'en' => 'United States'
        ],
        'c208' => [
            'zh' => '乌拉圭',
            'en' => 'Uruguay'
        ],
        'c209' => [
            'zh' => '前苏联',
            'en' => 'USSR'
        ],
        'c210' => [
            'zh' => '乌兹别克斯坦',
            'en' => 'Uzbekistan'
        ],
        'c211' => [
            'zh' => '瓦努阿鲁',
            'en' => 'Vanuatu'
        ],
        'c212' => [
            'zh' => '梵蒂岗',
            'en' => 'Vatican City State'
        ],
        'c213' => [
            'zh' => '委内瑞拉',
            'en' => 'Venezuela'
        ],
        'c214' => [
            'zh' => '越南',
            'en' => 'Viet Nam'
        ],
        'c215' => [
            'zh' => '英属维京群岛',
            'en' => 'Virgin Islands'
        ],
        'c216' => [
            'zh' => '美属维京群岛',
            'en' => 'Virgin Islands'
        ],
        'c217' => [
            'zh' => '瓦里斯和福图纳群岛',
            'en' => 'Wallis and Futuna Islands'
        ],
        'c218' => [
            'zh' => '西撒哈拉',
            'en' => 'Western Sahara'
        ],
        'c219' => [
            'zh' => '也门',
            'en' => 'Yemen'
        ],
        'c220' => [
            'zh' => '南斯拉夫',
            'en' => 'Yugoslavia'
        ],
        'c221' => [
            'zh' => '赞比亚',
            'en' => 'Zambia'
        ],
        'c222' => [
            'zh' => '扎伊尔',
            'en' => 'Zaire'
        ],
        'c223' => [
            'zh' => '津巴布韦',
            'en' => 'Zimbabwe'
        ]
    ];

    /**
     * @var array
     *
     * 母语查询
     */
    static $motherTongue = [
        [
            'id' => '1',
            'name' => 'English'
        ],
        [
            'id' => '2',
            'name' => '韩语'
        ],
        [
            'id' => '3',
            'name' => '中文简体'
        ]
    ];

    /**
     * @var array
     *
     * 兴趣查询
     * 学习汉语的目的
     */
    static $objective = [

        [
           'id' => '1',
            'name' => '通过HSK考试'
        ],
        [
            'id' => '2',
            'name' => '到中国留学'
        ],
        [
            'id' => '3',
            'name' => '对找工作有帮助'
        ],
        [
            'id' => '4',
            'name' => '对中国文化有兴趣'
        ],
        [
            'id' => '5',
            'name' => '更好地与家人沟通'
        ],
        [
            'id' => '6',
            'name' => '需要阅读中文资料'
        ],
        [
            'id' => '7',
            'name' => '与中国人做生意'
        ],
        [
            'id' => '8',
            'name' => '有助于升职'
        ],
        [
            'id' => '9',
            'name' => '到中国旅游'
        ],
        [
            'id' => '10',
            'name' => '交中国朋友'
        ],
        [
            'id' => '11',
            'name' => '去中国工作'
        ],
        [
            'id' => '12',
            'name' => '看中国电影'
        ],
        [
            'id' => '13',
            'name' => '喜欢中国的明星'
        ]
    ];

    /**
     * @var array
     *
     * 学习时间查询
     */
    static $learnTime = [
        [
            'id' => '1',
            'name' => '15分钟',
            'time' => '0.25',
            'content' => '慢慢来，也不错！'
        ],
        [
            'id' => '2',
            'name' => '30分钟',
            'time' => '0.5',
            'content' => '想更快成为汉语通也可以每天多用一点时间啊。'
        ],
        [
            'id' => '3',
            'name' => '1小时',
            'time' => '1',
            'content' => '我们一起迎接每天的进步吧。'
        ],
        [
            'id' => '4',
            'name' => '1.5小时',
            'time' => '1.5',
            'content' => '加油，别忘了每天的约定哦。'
        ],
        [
            'id' => '5',
            'name' => '2小时',
            'time' => '2',
            'content' => '你太棒了，坚持？天你就是汉语达人了。'
        ],
        [
            'id' => '6',
            'name' => '2.5小时',
            'time' => '2.5',
            'content' => '我们一起迎接每天的进步吧。'
        ],
        [
            'id' => '7',
            'name' => '3小时',
            'time' => '3',
            'content' => '我们一起迎接每天的进步吧。'
        ]
    ];
    /**
     * @var array
     *
     * 存库
     *
     */
    static $objectives = [

        'a1' => [
            'id' => '1',
            'name' => '通过HSK考试'
        ],
        'a2' => [
            'id' => '2',
            'name' => '到中国留学'
        ],
        'a3' => [
            'id' => '3',
            'name' => '对找工作有帮助'
        ],
        'a4' => [
            'id' => '4',
            'name' => '对中国文化有兴趣'
        ],
        'a5' => [
            'id' => '5',
            'name' => '更好地与家人沟通'
        ],
        'a6' => [
            'id' => '6',
            'name' => '需要阅读中文资料'
        ],
        'a7' => [
            'id' => '7',
            'name' => '与中国人做生意'
        ],
        'a8' => [
            'id' => '8',
            'name' => '有助于升职'
        ],
        'a9' => [
            'id' => '9',
            'name' => '到中国旅游'
        ],
        'a10' => [
            'id' => '10',
            'name' => '交中国朋友'
        ],
        'a11' => [
            'id' => '11',
            'name' => '去中国工作'
        ],
        'a12' => [
            'id' => '12',
            'name' => '看中国电影'
        ],
        'a13' => [
            'id' => '13',
            'name' => '喜欢中国的明星'
        ]
    ];
}
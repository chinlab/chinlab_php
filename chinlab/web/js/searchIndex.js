var d = $("#info_searchSelect").html();
var e = $.parseJSON(d);

var dataIndex = {};
//格式化筛选条件
function district(boxname, datetype) {
    var str = '';
    var district_list = e.district;
    for (var i = 0; i < district_list.length; i++) {
        str += '<li class="pull-left"><a class="clickOver" data-type="' + datetype + '" data-field="district_id" data-id="' + district_list[i].district_id + '" data-page="1" >' + district_list[i].district_name + '</a></li>'
        dataIndex['district_id' + district_list[i].district_id] = district_list[i].district_name;
    }
    var liststr = '<div class="select col-sm-12"><div class="pull-left col-sm-1 tag"><span>地区：</span></div><ul class="pull-left col-sm-10 value_list">' + str + '</ul><div class="pull-left col-sm-1 more"></div></div>';
    $("." + boxname).html(liststr);
    if(district_list.length>9){
		for(var j = district_list.length - 1;j>8;j--){
    		$("." + boxname).find('.value_list li').eq(j).addClass('isHide');
    	}
    	var more_str = '<a class="morebtn" data-toggle="true">更多<span class="glyphicon icon-angle-down" aria-hidden="true"></span></a>'
    	$("." + boxname).find('.more').html(more_str);
    }
}

function hospitalLevel(boxname, datetype) {
    var str = '';
    var hospitalLevel_list = e.hospitalLevel;
    for (var i = 0; i < hospitalLevel_list.length; i++) {
        str += '<li class="pull-left"><a class="clickOver" data-type="' + datetype + '" data-field="hospital_level" data-id="' + hospitalLevel_list[i].val + '" data-page="1" >' + hospitalLevel_list[i].name + '</a></li>'
        dataIndex['hospital_level' + hospitalLevel_list[i].val] = hospitalLevel_list[i].name;
    }
    var liststr = '<div class="select col-sm-12"><div class="pull-left col-sm-1 tag"><span>医院等级：</span></div><ul class="pull-left col-sm-10 value_list">' + str + '</ul><div class="pull-left col-sm-1 more"></div></div>';
    $("." + boxname).html(liststr);
    if(hospitalLevel_list.length>9){
		for(var j = hospitalLevel_list.length - 1;j>8;j--){
    		$("." + boxname).find('.value_list li').eq(j).addClass('isHide');
    	}
    	var more_str = '<a class="morebtn" data-toggle="true">更多<span class="glyphicon icon-angle-down" aria-hidden="true"></span></a>'
    	$("." + boxname).find('.more').html(more_str);
    }
}

function sections(boxname, datetype) {
    var str = '';
    var sections_list = e.sections;
    
    for (var i = 0; i < sections_list.length; i++) {
        str += '<li class="pull-left"><a class="clickOver" data-type="' + datetype + '" data-field="section_id" data-id="' + sections_list[i].section_id + '" data-page="1" >' + sections_list[i].section_name + '</a></li>'
        dataIndex['section_id' + sections_list[i].section_id] = sections_list[i].section_name;
    }
    var liststr = '<div class="select col-sm-12"><div class="pull-left col-sm-1 tag"><span>科室：</span></div><ul class="pull-left col-sm-10 value_list">' + str + '</ul><div class="pull-left col-sm-1 more"></div></div>';
    $("." + boxname).html(liststr);
    if(sections_list.length>9){
		for(var j = sections_list.length - 1;j>8;j--){
    		$("." + boxname).find('.value_list li').eq(j).addClass('isHide');
    	}
    	var more_str = '<a class="morebtn" data-toggle="true">更多<span class="glyphicon icon-angle-down" aria-hidden="true"></span></a>'
    	$("." + boxname).find('.more').html(more_str);
    }
   
    
}

function doctorPosition(boxname, datetype) {
    var str = '';
    var doctorPosition_list = e.doctorPosition;
    for (var i = 0; i < doctorPosition_list.length; i++) {
        str += '<li class="pull-left"><a class="clickOver" data-type="' + datetype + '" data-field="doctor_position" data-id="' + doctorPosition_list[i].val + '" data-page="1" >' + doctorPosition_list[i].name + '</a></li>'
        dataIndex['doctor_position' + doctorPosition_list[i].val] = doctorPosition_list[i].name;
    }
    dataIndex['visit_time8110'] = "星期一 上午";
    dataIndex['visit_time8101'] = "星期一 下午";
    dataIndex['visit_time8210'] = "星期二 上午";
    dataIndex['visit_time8201'] = "星期二 下午";
    dataIndex['visit_time8310'] = "星期三 上午";
    dataIndex['visit_time8301'] = "星期三 下午";
    dataIndex['visit_time8410'] = "星期四 上午";
    dataIndex['visit_time8401'] = "星期四 下午";
    dataIndex['visit_time8510'] = "星期五 上午";
    dataIndex['visit_time8501'] = "星期五 上午";
    dataIndex['visit_time8610'] = "星期六 上午";
    dataIndex['visit_time8601'] = "星期六 下午";
    dataIndex['visit_time8710'] = "星期日 上午";
    dataIndex['visit_time8701'] = "星期日 下午";
    var liststr = '<div class="select col-sm-12"><div class="pull-left col-sm-1 tag"><span>职称：</span></div><ul class="pull-left col-sm-10 value_list">' + str + '</ul><div class="pull-left col-sm-1 more"></div></div>';
    $("." + boxname).html(liststr);
    if(doctorPosition_list.length>9){
		for(var j = doctorPosition_list.length - 1;j>8;j--){
    		$("." + boxname).find('.value_list li').eq(j).addClass('isHide');
    	}
    	var more_str = '<a class="morebtn" data-toggle="true">更多<span class="glyphicon icon-angle-down" aria-hidden="true"></span></a>'
    	$("." + boxname).find('.more').html(more_str);
    }
}

function visitTime(boxname, datatype) {
    var reg=new RegExp("hospitalDoctor","g");
    var liststr = $("#date-lines").html().replace(reg,datatype);
    $("." + boxname).html(liststr);
}

//格式化表格
function hospital_table() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospital"><thead><tr><th>ID</th><th>医院名称</th><th>医院等级</th><th>所在地区</th><th>科室数量</th><th>医生数量</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function hospitalSection_table() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospitalSection"><thead><tr><th>ID</th><th>科室名称</th><th>医生数量</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function hospitalDoctor_table() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospitalDoctor"><thead><tr><th>ID</th><th>医生姓名</th><th>专业职称</th><th>出诊时间</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function section_hospital() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospital"><thead><tr><th>ID</th><th>医院名称</th><th>所在地区</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function doctor_table() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospitalDoctor"><thead><tr><th>ID</th><th>医生姓名</th><th>专业职称</th><th>所属医院</th><th>所属科室</th><th>出诊时间</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function disease_table() {
    $("#datatable_hospital_wrapper").html('');
    var tablestr = '<table class="table table-striped table-bordered table-hover" id="datatable_hospital"><thead><tr><th>ID</th><th>医院名称</th><th>所在地区</th><th>医生数量</th></tr></thead></table>';
    $("#datatable_hospital_wrapper").html(tablestr);
}

function in_array(search, array) {
    for (var i in array) {
        if (array[i] == search) {
            return true;
        }
    }
    return false;
}

$(document).ready(function () {

    function searchObj() {
        this.nowPage = "hospital";
        var own = this;
        //页面配置
        this.page = {
            hospital: {
                commitField: {
                    hospital_name: "",
                    district_id: "",
                    hospital_level: "",
                },
                fieldOther: [],
                inputName: 'hospital_name',
                FieldMap: [{
                    field: 'hospital_name',
                    dtype: 'input'
                }, {
                    field: 'district_id',
                    dtype: 'click'
                }, {
                    field: 'hospital_level',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
					$("#hospital_search").removeAttr('disabled');
					$(".input_search_info").removeAttr('disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    hospital_table();
                    district("district_warp", "hospital");
                    hospitalLevel("hospitalLevel_warp", "hospital");
                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="hospital">医院 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $("#list_desc").html("医院列表");
                    $(".nav_list_hospital").html(statusStr);
					//console.log(this.commitField)
                    var that = this;
                    var datatable_hospital = $('#datatable_hospital').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/hospital.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_name = that.commitField.hospital_name;
                                d.district_id = that.commitField.district_id;
                                d.hospital_level = that.commitField.hospital_level;
                                d.search_type = "1";
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": "hospital_name"
                            },
                            {
                                "data": "hospital_level_desc"
                            },
                            {
                                "data": "district_address"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                        "columnDefs": [{
                            "targets": [4],
                            "data": function (data) {
                                return data;
                            },
                            "render": function (data, type, full) {

                                return '<a class="clickOver" data-type="hospitalSection" data-field="hospital_id"  data-id="' + data.hospital_id + '" data-hospitalname="' + data.hospital_name + '" data-section="" data-page="0" href="javascript:void(0)">' + data.sections_num + '</a>';
                            }
                        },
                            {
                                "targets": [5],
                                "data": function (data) {
                                    return data;
                                },
                                "render": function (data, type, full) {
                                    return '<a class="clickOver" data-type="hospitalDoctor" data-field="hospital_id"  data-id="' + data.hospital_id + '" data-hospitalname="' + data.hospital_name + '" data-section="" data-page="0" href="javascript:void(0)">' + data.doctors_num + '</a>';
                                }
                            },
                        ]
                    });

                }
            },
            hospitalSection: {
                commitField: {
                    hospital_id: "",
                    section_id: "",
                },
                fieldOther: ['hospital_id'],
                inputName: 'hospital_name',
                FieldMap: [{
                    field: 'hospital_id',
                    dtype: 'click'
                }, {
                    field: 'section_id',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
					$("#hospital_search").attr('disabled','disabled');
					$(".input_search_info").attr('disabled','disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    hospitalSection_table();
                    sections("sections_warp", "hospitalSection");
                    $("#list_desc").html("科室列表");
                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="hospital">医院 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            if (v.sty == "1") {
                                statusStr += '<li class="pull-left"><a class="clickDelete" data-type="hospitalSection" data-field="' + (k + 1) + '" data-type="hospitalSection">' + v.desc + '<span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                            } else {
                                statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                            }
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospitalSection = $('#datatable_hospitalSection').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/section.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_id = that.commitField.hospital_id;
                                d.section_id = that.commitField.section_id;
                                d.type = 2;
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": "section_name"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                        "columnDefs": [{
                            "targets": [2],
                            "data": function (data) {
                                return data;
                            },
                            "render": function (data, type, full) {
                                return '<a class="clickOver" data-type="hospitalDoctor" data-field="hospital_id" data-id="' + data.hospital_id + '" data-issection="1" data-sectionName="section_id" data-sectionId="'+ data.section_id +'" data-hospitalname="' + data.hospital_name + '" data-section="' + data.section_name + '" data-page="0" href="javascript:void(0)">' + data.doctors_num + '</a>';
                            }
                        }]
                    });

                }
            },
            hospitalDoctor: {
                commitField: {
                    hospital_id: "",
                    section_id: "",
                    hospital_name: "",
                    doctor_position: "",
                    visit_time: "",
                },
                fieldOther: ['hospital_id', 'section_id'],
                inputName: 'hospital_name',
                FieldMap: [{
                    field: 'hospital_id',
                    dtype: 'click'
                }, {
                    field: 'section_id',
                    dtype: 'click'
                }, {
                    field: 'hospital_name',
                    dtype: 'input'
                }, {
                    field: 'doctor_position',
                    dtype: 'click'
                }, {
                    field: 'visit_time',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
                    $("#hospital_search").attr('disabled','disabled');
                    $(".input_search_info").attr('disabled','disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    doctorPosition("doctorPosition_warp", "hospitalDoctor");
                    visitTime("visitTime_warp", "hospitalDoctor");
                    hospitalDoctor_table();
                    $("#list_desc").html("医生列表");
                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="hospital">医院 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';

                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            if (v.sty == "1") {

                                statusStr += '<li class="pull-left"><a class="clickDelete" data-field="' + (k + 1) + '" data-type="' + v.dataType + '">' + v.desc + '<span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                            } else {
                                statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                            }
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospitalDoctor = $('#datatable_hospitalDoctor').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/doctor.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_id = that.commitField.hospital_id;
                                d.section_id = that.commitField.section_id;
                                d.hospital_name = that.commitField.hospital_name;
                                d.doctor_position = that.commitField.doctor_position;
                                d.visit_time = that.commitField.visit_time;
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": function (data) {
                                    return '<a class="catDoctorDetail" href="javascript:void(0)" doctor_id="'+data.doctor_id +'">' + data.doctor_name + '</a>';
                                }
                            },
                            {
                                "data": function (data) {
                                    return dataIndex['doctor_position' + data.doctor_position];
                                },
                            },
                            {
                                "data": "visit_time"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                    });

                }
            },
            sectionHospital: {
                commitField: {
                    section_name: "",
                    district_id: "",
                    section_id: "",
                    hospital_id: "",
                },
                fieldOther: [],
                inputName: 'section_name',
                FieldMap: [{
                    field: 'section_name',
                    dtype: 'input'
                }, {
                    field: 'hospital_id',
                    dtype: 'click'
                },{
                    field: 'district_id',
                    dtype: 'click'
                },  {
                    field: 'section_id',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
					$("#hospital_search").removeAttr('disabled');
					$(".input_search_info").removeAttr('disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    section_hospital();
                    district("district_warp", "sectionHospital");
                    sections("sections_warp", "sectionHospital");
                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="sectionHospital">医院 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospital = $('#datatable_hospital').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/hospital.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.section_name = that.commitField.section_name;
                                d.district_id = that.commitField.district_id;
                                d.hospital_level = that.commitField.hospital_level;
                                d.section_id = that.commitField.section_id;
                                d.search_type = "2";
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": function(data) {
                                    return '<a class="clickOver" data-type="section" data-field="hospital_id"  data-id="' + data.hospital_id + '" data-hospitalname="' + data.hospital_name + '" data-section="" data-page="0" href="javascript:void(0)">' + data.hospital_name + '</a>';
                                }
                            },
                            {
                                "data": "district_address"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                        "columnDefs": []
                    });

                }
            },
            section: {
                commitField: {
                    section_name: "",
                    district_id: "",
                    section_id: "",
                    hospital_id: "",
                },
                fieldOther: ['hospital_id'],
                inputName: 'section_name',
                FieldMap: [{
                    field: 'section_name',
                    dtype: 'input'
                }, {
                    field: 'hospital_id',
                    dtype: 'click'
                }, {
                    field: 'section_id',
                    dtype: 'click'
                }],
                historys: ['hospital_id'],
                tableFunction: function (inputObj) {
					$("#hospital_search").attr('disabled','disabled');
					$(".input_search_info").attr('disabled','disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    hospitalSection_table();
                    sections("sections_warp", "section");

                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="sectionHospital">医院 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            if (v.sty == "1") {
                                statusStr += '<li class="pull-left"><a class="clickDelete" data-type="section" data-field="' + (k + 1) + '">' + v.desc + '<span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                            } else {
                                statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                            }
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospitalSection = $('#datatable_hospitalSection').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/section.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_id = that.commitField.hospital_id;
                                d.section_name = that.commitField.section_name;
                                d.district_id = that.commitField.district_id;
                                d.hospital_level = that.commitField.hospital_level;
                                d.section_id = that.commitField.section_id;
                                d.type = 1;
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": function(data) {
                                    return '<a class="catSectionDetail" href="javascript:void(0)" hospital_id="'+data.hospital_id +'" section_id="'+data.section_id+'">' + data.section_name + '</a>';
                                }
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                        "columnDefs": [{
                            "targets": [2],
                            "data": function (data) {
                                return data;
                            },
                            "render": function (data, type, full) {
                                return data.doctors_num;
                            }
                        }]
                    });

                }
            },
            doctor: {
                commitField: {
                    hospital_id: "",
                    section_id: "",
                    doctor_name: "",
                    hospital_name: "",
                    doctor_position: "",
                    visit_time: "",
                    district_id: "",
                },
                fieldOther: [],
                inputName: 'doctor_name',
                FieldMap: [{
                    field: 'hospital_id',
                    dtype: 'click'
                }, {
                    field: 'district_id',
                    dtype: 'click'
                },{
                    field: 'section_id',
                    dtype: 'click'
                }, {
                    field: 'doctor_name',
                    dtype: 'input'
                },  {
                    field: 'hospital_name',
                    dtype: 'input'
                }, {
                    field: 'doctor_position',
                    dtype: 'click'
                }, {
                    field: 'visit_time',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
                    $("#hospital_search").removeAttr('disabled');
                    $(".input_search_info").removeAttr('disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    district("district_warp", "doctor");
                    doctorPosition("doctorPosition_warp", "doctor");
                    sections("sections_warp", "doctor");
                    visitTime("visitTime_warp", "doctor");
                    doctor_table();

                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="doctor">医生 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';

                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            if (v.sty == "1") {

                                statusStr += '<li class="pull-left"><a class="clickDelete" data-field="' + (k + 1) + '" data-type="' + v.dataType + '">' + v.desc + '<span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                            } else {
                                statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                            }
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospitalDoctor = $('#datatable_hospitalDoctor').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/doctor.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_id = that.commitField.hospital_id;
                                d.district_id = that.commitField.district_id;
                                d.section_id = that.commitField.section_id;
                                d.doctor_name = that.commitField.doctor_name;
                                d.hospital_name = that.commitField.hospital_name;
                                d.doctor_position = that.commitField.doctor_position;
                                d.visit_time = that.commitField.visit_time;
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": function (data) {
                                    return '<a class="catDoctorDetail" href="javascript:void(0)" doctor_id="'+data.doctor_id +'">' + data.doctor_name + '</a>';
                                }
                            },
                            {
                                "data": function (data) {
                                    return dataIndex['doctor_position' + data.doctor_position];
                                },
                            },
                            {
                                "data": "hospital_name"
                            },
                            {
                                "data": "section_info"
                            },
                            {
                                "data": "visit_time"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                    });

                }
            },
            disease: {
                commitField: {
                    disease_name: "",
                    district_id: "",
                    section_id: "",
                },
                fieldOther: [],
                inputName: 'disease_name',
                FieldMap: [{
                    field: 'disease_name',
                    dtype: 'input'
                }, {
                    field: 'district_id',
                    dtype: 'click'
                }, {
                    field: 'section_id',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
					$("#hospital_search").removeAttr('disabled');
					$(".input_search_info").removeAttr('disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    disease_table();
                    sections("sections_warp", "disease");
                    district("district_warp", "disease");
                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="disease">疾病 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospital = $('#datatable_hospital').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/hospital.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.disease_name = that.commitField.disease_name;
                                d.district_id = that.commitField.district_id;
                                d.hospital_level = that.commitField.hospital_level;
                                d.section_id = that.commitField.section_id;
                                d.search_type = "3";
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": "hospital_name"
                            },
                            {
                                "data": "district_address"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                        "columnDefs": [
                            {
                                "targets": [3],
                                "data": function (data) {
                                    return data;
                                },
                                "render": function (data, type, full) {
                                    return '<a class="clickOver" data-type="diseaseDoctor" data-field="hospital_id"  data-id="' + data.hospital_id + '" data-hospitalname="' + data.hospital_name + '" data-section="" data-page="0" href="javascript:void(0)">' + data.doctors_num + '</a>';
                                }
                            },
                        ]
                    });

                }
            },
            diseaseDoctor: {
                commitField: {
                    hospital_id: "",
                    section_id: "",
                    hospital_name: "",
                    doctor_position: "",
                    visit_time: "",
                },
                fieldOther: ['hospital_id', 'section_id'],
                inputName: 'hospital_name',
                FieldMap: [{
                    field: 'hospital_id',
                    dtype: 'click'
                }, {
                    field: 'section_id',
                    dtype: 'click'
                }, {
                    field: 'hospital_name',
                    dtype: 'input'
                }, {
                    field: 'doctor_position',
                    dtype: 'click'
                }, {
                    field: 'visit_time',
                    dtype: 'click'
                }],
                historys: [],
                tableFunction: function (inputObj) {
                    //重置页面为空
                    $("#hospital_search").attr('disabled','disabled');
                    $(".input_search_info").attr('disabled','disabled');
                    $(".district_warp").html("");
                    $(".hospitalLevel_warp").html("");
                    $(".sections_warp").html("");
                    $(".doctorPosition_warp").html("");
                    $(".visitTime_warp").html("");
                    doctorPosition("doctorPosition_warp", "diseaseDoctor");
                    visitTime("visitTime_warp", "diseaseDoctor");
                    doctor_table();

                    var statusStr = '<li class="pull-left"><a class="clickDelete" data-field="0" data-type="disease">疾病 <span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';

                    $.each(this.historys, function (k, v) {

                        if (v.dtype == "click") {
                            if (v.sty == "1") {

                                statusStr += '<li class="pull-left"><a class="clickDelete" data-field="' + (k + 1) + '" data-type="' + v.dataType + '">' + v.desc + '<span class="glyphicon icon-chevron-right" aria-hidden="true"></span></a></li>';
                            } else {
                                statusStr += '<li class="pull-left"><a class="keywords">' + v.desc + '<span class="glyphicon icon-remove clickDelete" data-page="1" data-field="' + v.field + '" data-type="' + v.dataType + '"aria-hidden="true"></span></a></li>';
                            }
                        } else {
                            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
                            $("#" + itemName).val(v.val);
                        }
                    });
                    $(".nav_list_hospital").html(statusStr);

                    var that = this;
                    var datatable_hospitalDoctor = $('#datatable_hospitalDoctor').DataTable({
                        "processing": true,
                        "serverSide": true,
                        "searching": false,
                        "ordering": false,
                        "bLengthChange": false, //去掉每页显示多少条数据方法
                        "iDisplayLength": 20,
                        "stateSave": false,
                        "ajax": {
                            url: "/search/doctor.php", // json datasource
                            type: "post", // method  , by default get
                            error: function () { // error handling
                                $(".employee-grid-error").html("");
                                $("#employee-grid").append('<tbody class="employee-grid-error"><tr><th colspan="3">No data found in the server</th></tr></tbody>');
                                $("#employee-grid_processing").css("display", "none");
                            },
                            "data": function (d) {
                                //添加额外的参数传给服务器
                                d.hospital_id = that.commitField.hospital_id;
                                d.section_id = that.commitField.section_id;
                                d.doctor_name = that.commitField.doctor_name;
                                d.hospital_name = that.commitField.hospital_name;
                                d.doctor_position = that.commitField.doctor_position;
                                d.visit_time = that.commitField.visit_time;
                            }
                        },
                        "columns": [{
                            "data": "id"
                        },
                            {
                                "data": function (data) {
                                    return '<a class="catDoctorDetail" href="javascript:void(0)" doctor_id="'+data.doctor_id +'">' + data.doctor_name + '</a>';
                                }
                            },
                            {
                                "data": function (data) {
                                    return dataIndex['doctor_position' + data.doctor_position];
                                },
                            },
                            {
                                "data": "hospital_name"
                            },
                            {
                                "data": "section_info"
                            },
                            {
                                "data": "visit_time"
                            },
                        ],
                        "oLanguage": { //对表格国际化
                            "sLengthMenu": "每页显示 _MENU_条",
                            "sZeroRecords": "没有找到符合条件的数据",
                            "sInfo": "当前第 _START_ - _END_ 条　共计 _TOTAL_ 条",
                            "sInfoEmpty": "木有记录",
                            "sInfoFiltered": "(从 _MAX_ 条记录中过滤)",
                            "sSearch": "搜索：",
                            "oPaginate": {
                                "sFirst": "首页",
                                "sPrevious": "前一页",
                                "sNext": "后一页",
                                "sLast": "尾页"
                            }
                        },
                    });

                }
            }
        };

        //初始化历史记录
        this.init = function () {
            own.page.hospital.historys = [];
            own.page.hospitalSection.historys = [];
            own.page.hospitalDoctor.historys = [];
            own.page.sectionHospital.historys = [];
            own.page.section.historys = [];
            own.page.doctor.historys = [];
            own.page.disease.historys = [];
            own.page.diseaseDoctor.historys = [];
        };

        this.deleteOver = function (inputObj) {

            own.ownPage = inputObj.attr("data-type");
            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
            own.page[inputObj.attr("data-type")].commitField[own.page[inputObj.attr("data-type")].inputName] = $("#" + itemName).val();

            var countNotDelete = 0;

            if (inputObj.attr("data-field") == "2" || inputObj.attr("data-field") == "1" || inputObj.attr("data-field") == "0") {

                $.each(own.page[inputObj.attr("data-type")].historys, function (k, v) {
                    if (k >= parseInt(inputObj.attr("data-field")) && v.dtype != "input") {
                        own.page[inputObj.attr("data-type")].commitField[v.field] = "";
                    }
                });
                countNotDelete = parseInt(inputObj.attr("data-field"));
                own.page[inputObj.attr("data-type")].historys.splice(parseInt(inputObj.attr("data-field")), own.page[inputObj.attr("data-type")].historys.length - parseInt(inputObj.attr("data-field")))

            } else {
                $.each(own.page[inputObj.attr("data-type")].historys, function (k, v) {
                    if (v.sty == "1") {
                        countNotDelete = countNotDelete + 1;
                    }
                });
                own.page[inputObj.attr("data-type")].historys.splice(countNotDelete, own.page[inputObj.attr("data-type")].historys.length - countNotDelete)
                own.page[inputObj.attr("data-type")].commitField[inputObj.attr("data-field")] = "";
            }


            var index = countNotDelete;

            var lengths = own.page[inputObj.attr("data-type")].FieldMap.length;
            for (k = 0; k < lengths; k++) {
                v = own.page[inputObj.attr("data-type")].FieldMap[k];
                if (!in_array(v.field, own.page[inputObj.attr("data-type")].fieldOther)) {

                    if (typeof(own.page[inputObj.attr("data-type")].commitField[v.field]) != "undefined" && own.page[inputObj.attr("data-type")].commitField[v.field] != "") {
                        own.page[inputObj.attr("data-type")].historys[index] = {
                            desc: typeof(dataIndex[v.field + own.page[inputObj.attr("data-type")].commitField[v.field]]) != "undefined" ? dataIndex[v.field + own.page[inputObj.attr("data-type")].commitField[v.field]] : "",
                            field: v.field,
                            dtype: v.dtype,
                            val: own.page[inputObj.attr("data-type")].commitField[v.field],
                            dataType: inputObj.attr("data-type"),
                            sty: "2"
                        };

                        index = index + 1;
                    }
                }
            }
            own.page[inputObj.attr("data-type")].tableFunction(inputObj);
        };

        this.clickOver = function (inputObj) {
            own.ownPage = inputObj.attr("data-type");
            var itemName = inputObj.attr("data-type") + "_" + own.page[inputObj.attr("data-type")].inputName + "_val";
            own.page[inputObj.attr("data-type")].commitField[own.page[inputObj.attr("data-type")].inputName] = $("#" + itemName).val();        
            if (inputObj.attr('data-page') == "0") {
                //往别的页面跳的
                own.page[inputObj.attr("data-type")].historys = [];
                if(inputObj.attr("data-issection")==1){
                	own.page[inputObj.attr("data-type")].commitField[inputObj.attr("data-field")] = inputObj.attr("data-id");
                	own.page[inputObj.attr("data-type")].commitField[inputObj.attr("data-sectionName")] = inputObj.attr("data-sectionId");
                }else{
                	own.page[inputObj.attr("data-type")].commitField[inputObj.attr("data-field")] = inputObj.attr("data-id");
                }
                
                var index = 0;
                if (inputObj.attr('data-hospitalname') != '') {
                    own.page[inputObj.attr("data-type")].historys[index] = {
                        desc: inputObj.attr("data-hospitalname"),
                        field: inputObj.attr("data-field"),
                        dtype: "click",
                        val: inputObj.attr("data-id"),
                        dataType: inputObj.attr("data-type"),
                        sty: "1"
                    }
                    index = index + 1;

                }

                if (inputObj.attr('data-section') != '') {
                    own.page[inputObj.attr("data-type")].historys[0].dataType = "hospitalSection";
                    own.page[inputObj.attr("data-type")].historys[index] = {
                        desc: inputObj.attr("data-section"),
                        field: inputObj.attr("data-field"),
                        dtype: "click",
                        val: inputObj.attr("data-id"),
                        dataType: inputObj.attr("data-type"),
                        sty: "1"
                    }
                }
            } else {

                var countNotDelete = 0;
                $.each(own.page[inputObj.attr("data-type")].historys, function (k, v) {
                    if (v.sty == "1") {
                        countNotDelete = countNotDelete + 1;
                    }
                });
                own.page[inputObj.attr("data-type")].historys.splice(countNotDelete, own.page[inputObj.attr("data-type")].historys.length - countNotDelete)
                own.page[inputObj.attr("data-type")].commitField[inputObj.attr("data-field")] = inputObj.attr("data-id");
                var index = countNotDelete;
                var lengths = own.page[inputObj.attr("data-type")].FieldMap.length;
                for (k = 0; k < lengths; k++) {
                    v = own.page[inputObj.attr("data-type")].FieldMap[k];
                    if (!in_array(v.field, own.page[inputObj.attr("data-type")].fieldOther)) {

                        if (own.page[inputObj.attr("data-type")].commitField[v.field] != "") {

                            own.page[inputObj.attr("data-type")].historys[index] = {
                                desc: typeof(dataIndex[v.field + own.page[inputObj.attr("data-type")].commitField[v.field]]) != "undefined" ? dataIndex[v.field + own.page[inputObj.attr("data-type")].commitField[v.field]] : "",
                                field: v.field,
                                dtype: v.dtype,
                                val: own.page[inputObj.attr("data-type")].commitField[v.field],
                                dataType: inputObj.attr("data-type"),
                                sty: "2"
                            };

                            index = index + 1;
                        }
                    }
                }
            }

            own.page[inputObj.attr("data-type")].tableFunction(inputObj);
        };
    }

    var searchObjT = new searchObj();
    searchObjT.page.hospital.tableFunction();

    $(document).on('click', '.clickOver', function () {
        searchObjT.clickOver($(this));
    });

    $(document).on('click', '.clickDelete', function () {
        searchObjT.deleteOver($(this));
    });

    $(document).on('click', '.catDoctorDetail', function () {
        $.post(local + "/search/doctorinfo.php", {"doctor_id":$(this).attr("doctor_id")}, function (data) {
            var content = $('#doctor_detail').html();
            var addlayer = layer.open({
                type: 1,
                title: "医生详情",
                skin: 'layui-layer-rim', //加上边框
                area: ['900px', '800px'], //宽高
                content: content
            });
            var str = '';
            for(var p in data.data) {
                if (p == 'doctor_position') {
                    data.data.doctor_position = dataIndex['doctor_position' + data.data.doctor_position]
                }
                //console.log(p, data.data[p])
                if (typeof($("#" + p)) != "undefined") {
                    $("#h_" + p).val(data.data[p]);
                }
            }
        }, "json");
    });

    $(document).on('click', '.catSectionDetail', function () {
        $.post(local + "/search/sectioninfo.php", {"section_id":$(this).attr("section_id"), "hospital_id":$(this).attr("hospital_id")}, function (data) {
            var content = $('#section_detail').html();
            var addlayer = layer.open({
                type: 1,
                title: "科室详情",
                skin: 'layui-layer-rim', //加上边框
                area: ['900px', '800px'], //宽高
                content: content
            });
            var str = '';
            for(var p in data.data) {
                if (typeof($("#" + p)) != "undefined") {
                    $("#h_" + p).val(data.data[p]);
                }
            }
        }, "json");
    });

    //tab切换
    $('#myTab li').click(function (e) {
        e.preventDefault();
        $(this).find("a").tab('show');
        searchObjT.init();
        status = $(this).attr("data-status");
        $.each(searchObjT.page,function(k,v){
        	//console.log(v)
        	$.each(v,function(key,val){
        		//console.log(key)
        		if(key == "commitField"){
        			//console.log(this)
        			$.each(val, function(k,v) {    
        				val[k] = "";                                                          
        			});
        		}
        	})
        })
        if (status == 1) {
            $('.input_search_info').attr("id", 'hospital_' + searchObjT.page.hospital.inputName + "_val").val("");        
            searchObjT.page.hospital.tableFunction();
            $("#hospital_search").attr('data-type','hospital');
        } else if (status == 2) {
            $('.input_search_info').attr("id", 'sectionHospital_' + searchObjT.page.sectionHospital.inputName + "_val").val("");
            searchObjT.page.sectionHospital.tableFunction();
            $("#hospital_search").attr('data-type','sectionHospital');
        } else if (status == 3) {
            $('.input_search_info').attr("id", 'doctor_' + searchObjT.page.doctor.inputName + "_val").val("");
            searchObjT.page.doctor.tableFunction();
            $("#hospital_search").attr('data-type','doctor');
        } else if (status == 4) {
            $('.input_search_info').attr("id", 'disease_' + searchObjT.page.disease.inputName + "_val").val("");
            searchObjT.page.disease.tableFunction();
            $("#hospital_search").attr('data-type','disease');
        }
    });

    //出诊时间动态js
    $(document).on('mouseover', '.week', function () {
        $(this).siblings('.specificTime').css("display", "block");
    });
    $(document).on('mouseout', '.week', function () {
        $(this).siblings('.specificTime').css("display", "none");
    });
    $(document).on('mouseover', '.specificTime', function () {
        $(this).css("display", "block");
    });
    $(document).on('mouseout', '.specificTime', function () {
        $(this).css("display", "none");
    });
    
    
    
    $(document).on('click', '.morebtn', function () {
    	var toggle = $(this).attr('data-toggle');
    	if(toggle == "true"){
    		$(this).parent().siblings('ul').find('li').removeClass('isHide');
    		$(this).attr('data-toggle','false');
    	}else{
    		var li_length = $(this).parent().siblings('ul').find('li').length;
    		for(var i = li_length;i>8;i--){
    			$(this).parent().siblings('ul').find('li').eq(i).addClass('isHide')
    		}
    		$(this).attr('data-toggle','true');
    	}
    });
    
    

})
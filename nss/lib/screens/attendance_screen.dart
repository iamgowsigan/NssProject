import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:http/http.dart' as http;
import '../components/bottom_navigation.dart';
import '../components/relative_scale.dart';
import '../layout/cardStudent.dart';
import '../utils/app_settings.dart';
import '../utils/const.dart';
import '../utils/custom_calender.dart';
import '../utils/style_sheet.dart';
import '../utils/urls.dart';
import 'package:nss/components/pop.dart';

class AttendanceScreen extends StatefulWidget {
  const AttendanceScreen({super.key});

  @override
  _AttendanceScreenState createState() {
    return _AttendanceScreenState();
  }
}

class _AttendanceScreenState extends State<AttendanceScreen> with RelativeScale{
  final _scaffoldKey = GlobalKey<ScaffoldState>();
  late DateTime selectedDate = DateTime.now();
  String userId = '';
  String expert = '';
  String phone = '';
  String name = '';
  String profilePic = '';
  bool showProgress = false;
  List studentDetail = [];
  List<bool> isIconEnabled = [];
  int currentIndex = 0;

  Map data = Map();
  ScrollController _scrollController = ScrollController();

  @override
  void initState() {
    super.initState();
    getSharedStore();
    //requestMultiplePermissions();
  }

  Future<void> _selectDate(BuildContext context) async {
    final DateTime? picked = await showDatePicker(
      context: context,
      initialDate: selectedDate,
      firstDate: DateTime(2015, 8),
      lastDate: DateTime(2101),
    );
    if (picked != null && picked != selectedDate)
      setState(() {
        selectedDate = picked;
      });
  }

  @override
  void dispose() {
    super.dispose();
  }

  getSharedStore() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    setState(() {
      userId = prefs.getString(Const.SID) ?? '';
      userId = Provider.of<AppSetting>(context, listen: false).sid;
      expert =  Provider.of<AppSetting>(context, listen: false).expert;
      phone =  Provider.of<AppSetting>(context, listen: false).phone;

      name = Provider.of<AppSetting>(context, listen: false).name;
      //welcomeScreen = prefs.getString(Const.WELCOMESCREEN) ?? '0';
      _getServer();

    });
  }

  _getServer() async {
    setState(() {
      showProgress = true;
    });
    var body = {
      "key" : Const.APPKEY,
      "uid": userId.toString(),
    };
    final response = await http.post(Uri.parse(Urls.ListStudents),
        headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);

    data = json.decode(response.body);

    setState(() {
      if(data["success"] == true) {
        studentDetail=data["students"];
        for(int i=0; i<studentDetail.length; i++) {
          studentDetail[i]['a_status'] = 1;
          isIconEnabled.add(true);
        }
      }
    });
  }

  _saveAttendance() async {
    setState(() {
      showProgress = true;
    });

    List<Map<String, dynamic>> attendanceData = [];
    for (int i = 0; i < studentDetail.length; i++) {
      Map<String, dynamic> student = {
        'u_id': studentDetail[i]['u_id'],
        'a_status': studentDetail[i]['a_status'].toString()
      };
      attendanceData.add(student);
      var body = {
        "key": Const.APPKEY,
        "date": '${selectedDate.year}-${selectedDate.month}-${selectedDate.day}',
        "attendanceData": json.encode(attendanceData)
      };
      final response = await http.post(Uri.parse(Urls.AddAttendance),
          headers: {HttpHeaders.acceptHeader: Const.POSTHEADER}, body: body);

      data = json.decode(response.body);
    }
  }


  @override
  Widget build(BuildContext context) {
    userId = Provider.of<AppSetting>(context, listen: false).sid;
    profilePic = Provider.of<AppSetting>(context, listen: false).profile;
    initRelativeScaler(context);

    return MaterialApp(
        home: SafeArea(
          top: false,
          child: Scaffold(
            key: _scaffoldKey,
            extendBody: true,
            bottomNavigationBar: BottomNavigationWidget(ishome: true, mcontext: context, controller: _scrollController, order: 2),
            appBar: AppBar(
              backgroundColor: fc_bg,
              titleSpacing: 0,
              elevation: 0,
              centerTitle: true,
              automaticallyImplyLeading: false,
              leading: IconButton(onPressed: () {
                Navigator.of(context).pop(true);
              }, icon: Icon(Icons.arrow_back_ios, size: sy(l), color: fc_2,)),
              title: Container(
                child: Text(
                  'ATTENDANCE'.toUpperCase(),
                  style: TextStyle(
                    fontSize: sy(l),
                    color: fc_2,
                    fontWeight: FontWeight.bold,
                  ),
                ),
              ),
            ),
            body: Container(
              child: Stack(
                children: [
                  Positioned(child: _screenBody(),
                    top: 0,
                    left: 0,
                    right: 0,
                    bottom: 0,),
                  Positioned(
                      right: sy(15),
                      bottom: sy(50),
                      child: FloatingActionButton(
                        backgroundColor: ThemePrimary,
                        foregroundColor: Colors.white,
                        onPressed: () {
                          _saveAttendance();
                          Pop.successTop(context,"Attendance Updated");
                        },
                        child: Icon(Icons.save_alt_rounded),
                      )
                  ),
                ],
              ),
            ),
          ),
        )
    );
  }

  _screenBody() {
    return Container(
      width: MediaQuery.of(context).size.width,
      height: MediaQuery.of(context).size.height,
      color: fc_bg,
      padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        crossAxisAlignment: CrossAxisAlignment.center,
        children: [
          calenderWidget(),
          Expanded(
            child: Container(
              child: SingleChildScrollView(
                controller: _scrollController,
                scrollDirection: Axis.vertical,
                physics: BouncingScrollPhysics(),
                child: Column(
                  children: [
                    studentListWidget(),

                  ],
                ),
              ),
            ),
          ),


        ],
      ),
    );
  }

  calenderWidget() {
    return Container(
      width: MediaQuery.of(context).size.width,
      child: GestureDetector(
        onTap: () => _selectDate(context),
        child: Container(
          padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
          height: sy(45),
          decoration: BoxDecoration(
            color: ThemePrimary,
            borderRadius: BorderRadius.circular(sy(5)),
          ),
          child: Row(
            mainAxisAlignment: MainAxisAlignment.start,
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Container(
                  padding: EdgeInsets.fromLTRB(sy(5), sy(7), sy(5), sy(5)),
                  child: Icon(Icons.calendar_month_outlined,color:fc_bg,size: sy(xl),)),
              Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text( '${selectedDate.year}-${selectedDate.month}-${selectedDate.day}',
                    style: TextStyle(
                      color: fc_bg,
                      fontSize: sy(l),
                      fontWeight: FontWeight.w600,
                    ),),
                  Text('Attendance not yet recorded',
                    style: TextStyle(
                      color: fc_bg,
                      fontSize: sy(s),
                      fontWeight: FontWeight.w400,
                    ),),
                ],
              ),
              Spacer(),
              Container(
                padding: EdgeInsets.fromLTRB(sy(5), sy(10), sy(5), sy(5)),
                child: Icon(Icons.arrow_forward_ios, color: fc_bg, size: sy(l),),
              ),
            ],
          ),
        ),
      ),
    );
  }

  studentListWidget() {
    return Container(
      padding: EdgeInsets.fromLTRB(sy(5), sy(5), sy(5), sy(5)),
      child: Column(
        mainAxisAlignment: MainAxisAlignment.start,
        children: [
          Container(
            child: SingleChildScrollView(
              scrollDirection: Axis.vertical,
              child: Column(
                children: [
                  for(int i=0; i<studentDetail.length; i++)
                    Container(
                      margin: EdgeInsets.fromLTRB(sy(0), sy(0), sy(0), sy(0)),
                      child: ElevatedButton(
                        style: ElevatedButton.styleFrom(
                          backgroundColor: fc_bg,
                          //elevation: 2,
                          padding:EdgeInsets.zero,
                          foregroundColor: ThemePrimary,
                          shape:RoundedRectangleBorder(
                            borderRadius:BorderRadius.circular(sy(0)),
                          ),
                          surfaceTintColor:fc_bg,
                        ),
                        onPressed: () {},
                        child: cardStudentWidget(i),
                      ),
                    ),
                ],
              ),
            ),
          ),
        ],
      ),
    );
  }

  toggleIcon(int currentIndex) {
    apiTest(currentIndex.toString());
    setState(() {
      if (studentDetail[currentIndex]['a_status'] == 0) {
        // If isIconEnabled is true, set attendance status to "Present"
        studentDetail[currentIndex]['a_status'] = 1;
      } else {
        // If isIconEnabled is false, set attendance status to "Absent"
        studentDetail[currentIndex]['a_status'] = 0;
      }
      apiTest(studentDetail.toString());
    });
  }

  cardStudentWidget(int i) {
    return Container(
    width: MediaQuery.of(context).size.width,
    padding: EdgeInsets.fromLTRB(sy(0), sy(10), sy(0), sy(10)),
    child: Column(
      children: [
        Row(
          children: [
            Container(
              child: (studentDetail[i]['gender'] == 'male') ? ClipRRect(
                borderRadius: BorderRadius.circular(sy(50)),
                child: Image.asset('assets/images/user11.jpg', width: sy(30), height: sy(30),),
              ) : ClipRRect(
                borderRadius: BorderRadius.circular(sy(50)),
                child: Image.asset('assets/images/user16.jpg', width: sy(30), height: sy(30),),
              ),
            ),
            SizedBox(
              width: sy(8),
            ),
            Expanded(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(studentDetail[i]['name'].toString().toUpperCase(),
                    style: TextStyle(
                      color: fc_1,
                      fontSize: sy(n),
                      fontWeight: FontWeight.w500,
                    ),),
                  SizedBox(
                    height: sy(2),
                  ),
                  Text('9177'+studentDetail[i]['reg_no'].toString(),
                    style: TextStyle(
                      color: fc_3,
                      fontSize: sy(s),
                      fontWeight: FontWeight.w400,
                    ),),
                  SizedBox(
                    height: sy(2),
                  ),
                  if(studentDetail[i]['a_status'] == 1)
                    Text('Present',
                      style: TextStyle(
                        color: Colors.green,
                        fontSize: sy(s),
                        fontWeight: FontWeight.w400,
                      ),)
                  else Text('Absent',
                    style: TextStyle(
                      color: Colors.redAccent,
                      fontSize: sy(s),
                      fontWeight: FontWeight.w400,
                    ),),
                ],
              ),
            ),
            GestureDetector(
              onTap: () {
                toggleIcon(i);
              },
              child: Icon(
                Icons.verified_outlined,
                color: studentDetail[i]['a_status'] == 1 ? Colors.green : Colors.grey,
                size: sy(xl),
              ),
            ),
            SizedBox(
              width: sy(5),
            ),

          ],
        ),
      ],
    ),

  );}


}

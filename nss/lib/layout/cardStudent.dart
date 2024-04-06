import 'package:flutter/material.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/style_sheet.dart';
import 'package:nss/utils/urls.dart';

class cardStudent extends StatefulWidget {
  var arrayList;
  int i;
  GestureTapCallback favBtn;

  cardStudent({Key? key, required this.arrayList, required this.favBtn, required this.i}):super(key: key);

  @override
  State<cardStudent> createState() => _cardStudentState();
}

class _cardStudentState extends State<cardStudent> with RelativeScale{
  bool isIconEnabled = false;
  var arrItem;
  int i=0;

  @override
  void initState() {
    super.initState();
    arrItem = widget.arrayList;
  }

  void toggleIcon() {
    setState(() {
      isIconEnabled = !isIconEnabled;
      // Update the attendance status based on the isIconEnabled state
      if (isIconEnabled) {
        // If isIconEnabled is true, set attendance status to "Present"
        arrItem['a_status'] = '1';
      } else {
        // If isIconEnabled is false, set attendance status to "Absent"
        arrItem['a_status'] = '0';
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return Container(
      width: MediaQuery.of(context).size.width,
      padding: EdgeInsets.fromLTRB(sy(0), sy(10), sy(0), sy(10)),
      child: Column(
        children: [
          Row(
            children: [
              Container(
                child: (arrItem['gender'] == 'male') ? ClipRRect(
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
                    Text(arrItem['name'].toString().toUpperCase(),
                    style: TextStyle(
                      color: fc_1,
                      fontSize: sy(n),
                      fontWeight: FontWeight.w500,
                    ),),
                    SizedBox(
                      height: sy(2),
                    ),
                    Text('9177'+arrItem['reg_no'].toString(),
                      style: TextStyle(
                        color: fc_3,
                        fontSize: sy(s),
                        fontWeight: FontWeight.w400,
                      ),),
                    SizedBox(
                      height: sy(2),
                    ),
                    if(isIconEnabled)
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
                onTap: toggleIcon,
                child: Icon(
                  Icons.verified_outlined,
                  color: isIconEnabled ? Colors.green : Colors.grey,
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

    );
  }
}

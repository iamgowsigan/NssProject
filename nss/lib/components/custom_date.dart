import 'package:flutter/material.dart';
import 'package:intl/intl.dart';

class CustomeDate{

  static dateTime(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{


      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , false);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=date +'-'+ month+'-'+year;

      finalOut=splitdate+'   '+splitTime;

      return finalOut;
    }

  }
  static dateTimeCopy(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{


      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , false);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=date +'-'+ month+'-'+year;

      finalOut=splitdate+'   '+splitTime;

      return finalOut;
    }

  }
  static dateTime2(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , false);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      // if(month.length==1){
      //   month='0'+month;
      // }
      // if(date.length==1){
      //   date='0'+date;
      // }


      String splitdate=date +' '+ month+' '+year;

      finalOut= splitTime+'  |  '+splitdate;

      return finalOut;
    }

  }
  static date(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);

      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=date +'-'+ month+'-'+year;

      finalOut=splitdate;

      return finalOut;
    }

  }
  static dateMonth(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);

      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=month+'  '+year;

      finalOut=splitdate;

      return finalOut;
    }

  }
  static datedaymonth(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=date+' '+month;

      finalOut=splitdate;

      return finalOut;
    }

  }
  static dayMonthYear(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=date+' '+month+' '+year;

      finalOut=splitdate;

      return finalOut;
    }

  }
  static age(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Age';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MM').format(dt);
      String year = DateFormat('y').format(dt);
      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }

      int birthYear=DateTime.now().year-int.parse(DateFormat('y').format(dt));


      String splitdate=birthYear.toString()+' yrs';

      finalOut=splitdate;

      return finalOut;
    }

  }
  static ago(String userdate){

    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{

      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      final date2 = DateTime.now();
      final difference = date2.difference(dt);

      if ((difference.inDays / 7).floor() >= 1) {
        return   '1 week ago' ;
      } else if (difference.inDays >= 2) {
        return '${difference.inDays} days ago';
      } else if (difference.inDays >= 1) {
        return   '1 day ago'  ;
      } else if (difference.inHours >= 2) {
        return '${difference.inHours} hrs ago';
      } else if (difference.inHours >= 1) {
        return  '1 hrs ago' ;
      } else if (difference.inMinutes >= 2) {
        return '${difference.inMinutes} min ago';
      } else if (difference.inMinutes >= 1) {
        return   '1 min ago'  ;
      } else if (difference.inSeconds >= 3) {
        return '${difference.inSeconds} sec ago';
      } else {
        return 'now';
      }

    //  finalOut=difference.toString();

    //  return finalOut;
    }

  }
  static ddMMMYYYY(String userdate){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat('dd').format(dt);
      String month = DateFormat('MMM').format(dt);
      String year = DateFormat('y').format(dt);

      if(month.length==1){
        month='0'+month;
      }
      if(date.length==1){
        date='0'+date;
      }


      String splitdate=month+' '+date+', '+year;

      finalOut=splitdate;

      return finalOut;
    }

  }
  static Format(String userdate,String format){
    if(userdate==null || userdate=='null'){
      return 'Not Updated';
    }else{
      String finalOut='';
      String splitTime=DateConvert.getDated(userdate , true);
      DateTime dt = DateTime.parse(userdate);
      String defaultFormat=DateFormat('yyyy-MM-ddT07:12:50').format(dt);

      String date = DateFormat(format).format(dt);
      finalOut=date;

      return finalOut;
    }

  }
 }


//AM PM TIME
class DateConvert{

  static getDated(String dated, bool withdate){

    if(dated==null || dated=='' || dated=='null' ){

      return 'Not updated';
    }else{

      int morning, hour, min;
      String getdated;

      morning=0;
      String lab;
      hour=int.parse(dated.substring(11,13));
      min=int.parse(dated.substring(14,16));
      getdated=dated.substring(0,10);

      if(hour>12){
        lab='p.m';
      }else if(hour==12){
        lab='p.m';
      }else{
        lab='a.m';
      }

      if (hour > 23 || min > 59)
      {
        //add Zero
        String thour,tmin;
        if((hour.toString()).length==1){
          thour='0$hour';
        }else{
          thour='$hour';
        }

        if((min.toString()).length==1){
          tmin='0$min';
        }else{
          tmin='$min';
        }


        return (withdate==true)? "$getdated  $thour:$tmin $lab" : "$thour:$tmin $lab";

      }

      if (hour >= 12)
      {
        morning = 1;

        if (hour > 12)
        {
          hour -= 12;
        }

      }

      //if input is 00xx
      if (hour == 0)
      {
        morning = 2;
        hour = hour + 12;
      }
      else
      {
        morning = 0;
      }

      //print the result
      if (morning == 2)
      {
        //add Zero
        String thour,tmin;
        if((hour.toString()).length==1){
          thour='0$hour';
        }else{
          thour='$hour';
        }

        if((min.toString()).length==1){
          tmin='0$min';
        }else{
          tmin='$min';
        }

        return (withdate==true)? "$getdated  $thour:$tmin $lab" : "$thour:$tmin $lab";

      }
      if (morning == 0)
      {
        //add Zero
        String thour,tmin;
        if((hour.toString()).length==1){
          thour='0$hour';
        }else{
          thour='$hour';
        }

        if((min.toString()).length==1){
          tmin='0$min';
        }else{
          tmin='$min';
        }

        return (withdate==true)? "$getdated  $thour:$tmin $lab" : "$thour:$tmin $lab";
      }
      if (morning == 1)
      {
        //add Zero
        String thour,tmin;
        if((hour.toString()).length==1){
          thour='0$hour';
        }else{
          thour='$hour';
        }

        if((min.toString()).length==1){
          tmin='0$min';
        }else{
          tmin='$min';
        }

        return (withdate==true)? "$getdated  $thour:$tmin $lab" : "$thour:$tmin $lab";
      }


    }

  }






}

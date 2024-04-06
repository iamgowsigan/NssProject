import 'package:flutter/material.dart';
import 'package:flutter/services.dart';
import 'package:nss/components/mytext.dart';
import 'package:nss/components/relative_scale.dart';
import 'package:nss/utils/style_sheet.dart';

class CountWidget extends StatefulWidget {
  final ValueChanged<int>? onChanged;
   int initial=0;
   int min=0;
   int max=10000;

  CountWidget({Key? key,this.onChanged,this.initial=0,this.min=0,this.max=10000}) : super(key: key);


  @override
  _CountWidgetState createState() => _CountWidgetState();
}

class _CountWidgetState extends State<CountWidget> with RelativeScale {

  int item=0;
  int min=0;
  int max=10000;

  @override
  void initState() {
    item=widget.initial;
    min=widget.min;
    max=widget.max;
    super.initState();

  }

  @override
  void dispose() {
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    initRelativeScaler(context);
    return SizedBox(
      child: Container(
       // decoration: decoration_round(fc_textfield_bg, sy(50), sy(50), sy(50), sy(50)),
        child: Row(
          children: [

            ElevatedButton(
              style: ElevatedButton.styleFrom(
                padding: EdgeInsets.zero,
                  shape: CircleBorder(),
                  minimumSize: Size.zero,
                  backgroundColor: (item!=0 && item!=min)?ThemePrimary:Colors.grey.shade300,
                elevation: (item!=0 && item!=min)?1:0,


              ),
              onPressed: (){
                setState(() {

                  if(item!=0 && item!=min){
                    item=item-1;
                    widget.onChanged!(item);
                  }

                });
              },
              child: Container(

                padding: EdgeInsets.all(sy(4)),
                alignment: Alignment.center,
                child: Icon(Icons.remove,size: sy(n),color: Colors.white,),
              )
            ),
            Container(
              width: sy(22),
              alignment: Alignment.center,
              child: Mytext(item.toString(),fc_1,size: sy(n+1),),
            ),
            ElevatedButton(

              style: ElevatedButton.styleFrom(
                  padding: EdgeInsets.zero,
                shape: CircleBorder(),
                  minimumSize: Size.zero,
                backgroundColor: (item!=max)?ThemePrimary:Colors.grey.shade300,
                elevation: (item!=max)?1:0
              ),
              onPressed: (){
                setState(() {

                  if(item!=max){
                    item=item+1;
                    widget.onChanged!(item);
                  }

                });
              },
              child: Container(
                padding: EdgeInsets.all(sy(4)),
                alignment: Alignment.center,
                child: Icon(Icons.add,size: sy(n),color: Colors.white,),
              )
            ),
          ],
        ),
      ),
    );
  }

  /// This function Build and returns individual TextField item.
  ///
  /// * Requires a build context
  /// * Requires Int position of the field


}


import 'package:flutter/material.dart';

import '../utils/app_settings.dart';
import '../utils/style_sheet.dart';
import 'mytext.dart';

CustomDialog({required BuildContext context, String title='', required Widget child}) {
  showDialog(
    context: context,
    builder: (context) => AlertDialog(
      backgroundColor: fc_bg,
      shape: RoundedRectangleBorder(
          borderRadius: BorderRadius.all(Radius.circular(20.0))
      ),

      titlePadding:  (title!='')?null:EdgeInsets.zero,
      title: (title!='')?Mytext(title,fc_1,
        textAlign: TextAlign.center,bold: true,
      ):null,
      content: IntrinsicWidth(
        child: IntrinsicHeight(
          child : child,
        ),
      ),

    ),
  );
}
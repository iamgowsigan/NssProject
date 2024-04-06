import 'package:flutter/material.dart';

class CustomCalendar extends StatefulWidget {
  @override
  _CustomCalendarState createState() => _CustomCalendarState();
}

class _CustomCalendarState extends State<CustomCalendar> {
  late DateTime selectedDate = DateTime.now();

  @override
  void initState() {
    super.initState();
    // Initialize with the current date
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
  Widget build(BuildContext context) {
    return Column(
      mainAxisAlignment: MainAxisAlignment.center,
      crossAxisAlignment: CrossAxisAlignment.center,
      children: [
        Text(
          'Selected Date:',
          style: TextStyle(fontSize: 20),
        ),
        SizedBox(height: 10),
        Text(
          '${selectedDate.year}-${selectedDate.month}-${selectedDate.day}',
          style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
        ),
        SizedBox(height: 20),
        ElevatedButton(
          onPressed: () => _selectDate(context),
          child: Text('Select Date'),
        ),
        SizedBox(height: 20),
        ElevatedButton(
          onPressed: () {
            Navigator.pop(context, selectedDate); // Pass selected date back
          },
          child: Text('Go Back'),
        ),
      ],
    );
  }
}
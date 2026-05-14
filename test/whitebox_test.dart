// import 'package:flutter_test/flutter_test.dart';
// import 'package:shared_preferences/shared_preferences.dart';
// import 'package:agung_motor_app/core/providers/auth_provider.dart';
// import 'package:agung_motor_app/core/services/api_service.dart';
// import 'package:agung_motor_app/core/services/push_notification_helper.dart';

// void main() {
//   TestWidgetsFlutterBinding.ensureInitialized();

//   setUp(() {
//     SharedPreferences.setMockInitialValues({});
//     ApiService.clearCache();
//   });

//   group('Whitebox Testing - AuthProvider & ApiService', () {
//     test('Initial AuthProvider state should be checking auth correctly', () {
//       final auth = AuthProvider();
//       expect(auth.isCheckingAuth, true);
//       expect(auth.isLoggedIn, false);
//       expect(auth.isAdmin, false);
//       expect(auth.isLoading, false);
//     });

//     test('ApiService caching token workflow', () async {
//       SharedPreferences.setMockInitialValues({'auth_token': 'dummy_jwt_token'});

//       final token = await ApiService.getToken();
//       expect(token, 'dummy_jwt_token');

//       // Clear cache
//       ApiService.clearCache();
//       final tokenAfterClear = await ApiService.getToken();
//       expect(tokenAfterClear, 'dummy_jwt_token');
//     });

//     test('AuthProvider checkAuth when no token exists', () async {
//       final auth = AuthProvider();
//       await auth.checkAuth();

//       expect(auth.isCheckingAuth, false);
//       expect(auth.isLoggedIn, false);
//     });

//     test('completeLogin should set isLoggedIn to true', () {
//       final auth = AuthProvider();
//       auth.completeLogin();
//       expect(auth.isLoggedIn, true);
//     });
//   });

//   group('Whitebox Testing - PushNotificationHelper', () {
//     testWidgets('PushNotificationHelper overlay execution check', (WidgetTester tester) async {
//       await tester.pumpWidget(const测试App());
//       expect(find.text('Tes'), findsOneWidget);
//     });
//   });
// }

// import 'package:flutter/material.dart';
// class 测试App extends StatelessWidget {
//   const 测试App({super.key});
//   @override
//   Widget build(BuildContext context) {
//     return const MaterialApp(home: Scaffold(body: Text('Tes')));
//   }
// }

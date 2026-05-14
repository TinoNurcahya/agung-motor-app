import 'package:flutter/foundation.dart';
import 'package:shared_preferences/shared_preferences.dart';
import '../constants/api_constants.dart';
import '../services/api_service.dart';

class AuthProvider extends ChangeNotifier {
  bool _isLoggedIn = false;
  bool _isAdmin = false;
  bool _isLoading = false;
  bool _isCheckingAuth = true;
  Map<String, dynamic>? _user;
  String? _error;

  bool get isLoggedIn => _isLoggedIn;
  bool get isAdmin => _isAdmin;
  bool get isLoading => _isLoading;
  bool get isCheckingAuth => _isCheckingAuth;
  Map<String, dynamic>? get user => _user;
  String? get error => _error;
  String get userName => _user?['name'] ?? 'Admin';
  String get userEmail => _user?['email'] ?? '';

  Future<void> checkAuth() async {
    _isCheckingAuth = true;
    notifyListeners();

    await Future.delayed(const Duration(milliseconds: 2000));

    final prefs = await SharedPreferences.getInstance();
    final token = prefs.getString('auth_token');

    if (token != null) {
      final res = await ApiService.get(kUserEndpoint);
      if (res['success'] == true) {
        _user = res['data'] ?? res;
        _isLoggedIn = true;
        _isAdmin = (_user?['role'] == 'admin' || _user?['role'] == 'user' || _user?['role'] == null);
      } else {
        await prefs.remove('auth_token');
        ApiService.clearCache();
        _isLoggedIn = false;
      }
    }

    _isCheckingAuth = false;
    notifyListeners();
  }

  Future<bool> login(String email, String password, {bool rememberMe = true}) async {
    _isLoading = true;
    _error = null;
    notifyListeners();

    final res = await ApiService.post(
      kLoginEndpoint,
      {'email': email, 'password': password},
      withAuth: false,
    );

    _isLoading = false;

    if (res['success'] == true) {
      final token = res['data']?['token'] ?? res['token'];
      final prefs = await SharedPreferences.getInstance();
      if (token != null) {
        await prefs.setString('auth_token', token);
        ApiService.clearCache();
      }

      if (rememberMe) {
        await prefs.setString('remember_email', email);
        await prefs.setBool('remember_me', true);
      } else {
        await prefs.remove('remember_email');
        await prefs.setBool('remember_me', false);
      }

      _user = res['data']?['user'] ?? res['user'];
      _isAdmin = (_user?['role'] == 'admin' || _user?['role'] == 'user' || _user?['role'] == null);
      notifyListeners();
      return true;
    } else {
      _error = res['message'] ?? 'Login gagal';
      notifyListeners();
      return false;
    }
  }

  void completeLogin() {
    _isLoggedIn = true;
    notifyListeners();
  }

  Future<void> logout() async {
    await ApiService.post(kLogoutEndpoint, {});
    final prefs = await SharedPreferences.getInstance();
    await prefs.remove('auth_token');
    ApiService.clearCache();
    _isLoggedIn = false;
    _isAdmin = false;
    _user = null;
    notifyListeners();
  }
}

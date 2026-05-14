import 'dart:async';
import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:shared_preferences/shared_preferences.dart';

class ApiService {
  static String? _cachedToken;
  static bool _isTokenCached = false;

  static Future<String?> getToken() async {
    if (_isTokenCached) return _cachedToken;
    final prefs = await SharedPreferences.getInstance();
    _cachedToken = prefs.getString('auth_token');
    _isTokenCached = true;
    return _cachedToken;
  }

  static void clearCache() {
    _cachedToken = null;
    _isTokenCached = false;
  }

  static Future<Map<String, String>> _headers({bool withAuth = true}) async {
    final headers = {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
      'Connection': 'keep-alive',
    };
    if (withAuth) {
      final token = await getToken();
      if (token != null) headers['Authorization'] = 'Bearer $token';
    }
    return headers;
  }

  static Future<Map<String, dynamic>> get(String url) async {
    try {
      final response = await http.get(
        Uri.parse(url),
        headers: await _headers(),
      ).timeout(const Duration(seconds: 15));
      return _handleResponse(response);
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> post(
    String url,
    Map<String, dynamic> body, {
    bool withAuth = true,
  }) async {
    try {
      final response = await http.post(
        Uri.parse(url),
        headers: await _headers(withAuth: withAuth),
        body: jsonEncode(body),
      ).timeout(const Duration(seconds: 15));
      return _handleResponse(response);
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> put(
    String url,
    Map<String, dynamic> body,
  ) async {
    try {
      final response = await http.put(
        Uri.parse(url),
        headers: await _headers(),
        body: jsonEncode(body),
      ).timeout(const Duration(seconds: 15));
      return _handleResponse(response);
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  static Future<Map<String, dynamic>> delete(String url) async {
    try {
      final response = await http.delete(
        Uri.parse(url),
        headers: await _headers(),
      ).timeout(const Duration(seconds: 15));
      return _handleResponse(response);
    } catch (e) {
      return {'success': false, 'message': e.toString()};
    }
  }

  static Map<String, dynamic> _handleResponse(http.Response response) {
    try {
      final data = jsonDecode(response.body);
      if (response.statusCode >= 200 && response.statusCode < 300) {
        return {'success': true, ...data};
      } else {
        return {
          'success': false,
          'message': data['message'] ?? 'Terjadi kesalahan',
          'errors': data['errors'],
        };
      }
    } catch (e) {
      return {'success': false, 'message': 'Gagal memproses respons server'};
    }
  }

  // --- API DEBOUNCING MECHANISM ---
  static final Map<String, Timer> _debounceTimers = {};
  static final Map<String, Completer<Map<String, dynamic>>> _debounceCompleters = {};

  static Future<Map<String, dynamic>> getDebounced(
    String url, {
    Duration delay = const Duration(milliseconds: 400),
  }) {
    _debounceTimers[url]?.cancel();

    final completer = _debounceCompleters[url] ?? Completer<Map<String, dynamic>>();
    _debounceCompleters[url] = completer;

    _debounceTimers[url] = Timer(delay, () async {
      _debounceTimers.remove(url);
      _debounceCompleters.remove(url);
      final result = await get(url);
      if (!completer.isCompleted) {
        completer.complete(result);
      }
    });

    return completer.future;
  }
}

class Debouncer {
  final Duration delay;
  Timer? _timer;

  Debouncer({this.delay = const Duration(milliseconds: 500)});

  void run(FutureOr<void> Function() action) {
    _timer?.cancel();
    _timer = Timer(delay, () {
      action();
    });
  }

  void cancel() {
    _timer?.cancel();
  }
}

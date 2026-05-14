const String kBaseUrl = 'http://192.168.1.5:8000'; // Chrome Web / Localhost
// const String kBaseUrl = 'http://10.0.2.2:8000'; // Android emulator → localhost
// const String kBaseUrl = 'http://192.168.1.5:8000'; // Physical device → your PC IP

const String kApiBase = '$kBaseUrl/api';
const String kLoginEndpoint = '$kApiBase/login';
const String kLogoutEndpoint = '$kApiBase/logout';
const String kUserEndpoint = '$kApiBase/user';

const String kDashboardOverview = '$kApiBase/dashboard/overview';
const String kDashboardChart = '$kApiBase/dashboard/chart';
const String kDashboardRecent = '$kApiBase/dashboard/recent-transactions';

const String kPenghasilanBase = '$kApiBase/penghasilan';
const String kPengeluaranBase = '$kApiBase/pengeluaran';
const String kProdukBase = '$kApiBase/produk';

const String kStatistikSummary = '$kApiBase/statistik/summary';
const String kStatistikTrend = '$kApiBase/statistik/trend';
const String kStatistikChart = '$kApiBase/statistik/chart';

const String kAiPredictions = '$kApiBase/ai/predictions';
const String kAiStockHealth = '$kApiBase/ai/stock-health';
const String kAiCustomerRetention = '$kApiBase/ai/customer-retention';
const String kAiForecast = '$kApiBase/ai/forecast';
const String kAiRecommendations = '$kApiBase/ai/recommendations';
const String kAiRefresh = '$kApiBase/ai/refresh';

const String kSearch = '$kApiBase/search';

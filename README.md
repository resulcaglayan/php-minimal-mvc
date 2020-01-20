# php-minimal-mvc
 PHP Minimal MVC Project
## Ayarlar
1. Sunucu ve veritabanı ayarlarını değiştirmek için system/config.php dosyasını düzenleyin.
2. Veritabanı ile birleşik bir uygulama geliştirecekseniz DB_STATUS alanını true yapın.
3. Veritabanı ile birleşik bir uygulama için SET_SESSION ayarını db yapın.
4. Veritabanı kullanmak istemiyorsanız, statik bir projeniz varsa DB_STATUS false olmalıdır.

## Örnek Kullanımlar
```php
// title/description/keywords kullanımı
$this->document->setTitle("Page Title");
$this->document->setDescription("Page Description");
$this->document->setKeywords("Page Keywords");

// model kullanımı
$this->load->model('index');
$test = $this->model_index->test("Test Model");

// dahili controller kullanımı
$data['header'] = $this->load->controller('header');

// view kullanımı
$this->response->setOutput($this->load->view('index', $data));

// redirect kullanımı
$this->response->redirect("url");

// session kullanımı
$this->session->data['name'] = "test";

// get/post/cookie/files/server/request kullanımı
$test = $this->request->get['test']

// mysql_real_escape kullanımı
$this->db->escape($str);

// mysql_insert_id kullanımı
$this->db->getLastId();

// mysql_affected_rows kullanımı
$this->db->countAffected();

// mysql query kullanımı
$this->db->query("SELECT * FROM table");
```

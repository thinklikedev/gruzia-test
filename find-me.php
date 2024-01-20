<?php
header('Content-Type: text/html; charset=utf-8');
if (@$_POST['pswd'] == '555') :
    setcookie('find', '555', time() + 16000000, '/');
elseif (@$_COOKIE['find'] != '555') : ?>
    <form method="post">
        <input type="text" name="pswd">
        <input type="submit" value="Go">
    </form>

    <?php
    die;
endif;

output_find_form();

// Директории для поиска
$directories = ['.'];

$find = trim(@$_GET['find']);
$in_plugins = @$_GET['in_plugins'];
$ext_string = trim(@$_GET['ext']);
$ext = $ext_string ? explode(',', $ext_string) : [];

if (!$find) {
    echo '<p>Не задано значение поиска</p>';
    die;
} else if (!$ext) {
    echo '<p>Не заданы расширения файлов</p>';
    die;
}

$results = [];
function getDirContents($dir, &$results = array()) {
    global $in_plugins;

    $files = scandir($dir);

    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);

        if (!is_dir($path)) {
            foreach ($GLOBALS['ext'] as $key => $ext) {
                $ok = strpos($path, $ext) !== false ? 1 : 0;

                if (!$in_plugins) {
                    $ok = strpos($path, '/plugins') === false ? $ok : 0;
                }

                if ($ok) {
                    $results[] = $path;
                }

                break;
            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
        }
    }
}

foreach ($directories as $key => $value) {
    getDirContents($value, $results);
}

$matches = [];

foreach ($results as $key => $value) {
    $file = file_get_contents($value);

    if(strpos($file, $find) !== false) {
        $matches[] = preg_replace('{.*?/public_html/}', '', $value);
    }
}


echo '<p>Найдено совпадений: '. count($matches) .'</p>';
echo '<pre>';
foreach ($matches as $index => $value) {
    echo "[$index] $value\r\n";
}
echo '</pre>';





function output_find_form() { ?>
    <form>
        <div>
            <div>
                <p>Найти</p>
                <input type="text" name="find" value="<?php echo @$_GET['find']; ?>">
            </div>
            <div>
                <p>Расширение файлов</p>
                <input type="text" name="ext" value="<?php echo @$_GET['ext']; ?>">
            </div>
            <div>
                <p>Искать и в плагинах</p>
                <label class="in_plugins_label">
                    <input type="checkbox" name="in_plugins" <?php echo isset($_GET['in_plugins']) ? 'checked' : ''; ?>>
                    Да / Нет
                </label>
            </div>
            <div>
                <input type="submit" value="Искать">
            </div>
        </div>

        <style>
            body {
                padding-left: 50px;
                margin: 0;
                background: #7a7a7a;
                font-size: 16px;
                color: white;
            }
            form {
                padding-bottom: 30px;
                margin-bottom: 30px;
                border-bottom: 1px solid #d7d7d7;
            }
            form > div {
                display: flex;
                margin-bottom: 20px;
            }
            form > div > div {
                margin-right: 20px;
            }
            input[type=text] {
                width: 300px;
                font-size: 18px;
                padding: 5px 8px;
            }
            input[type=submit] {
                padding: 5px 15px;
                margin-top: 50px;
                font-size: 18px;
                cursor: pointer;
            }
            .in_plugins_label {
                display: block;
                padding-top: 5px;
            }
            input[type=checkbox] {
                width: 18px;
                height: 18px;
                cursor: pointer;
                vertical-align: middle;
            }
        </style>
    </form>
    <?php
}
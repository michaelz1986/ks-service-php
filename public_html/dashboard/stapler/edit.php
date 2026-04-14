<?php
require_once dirname(dirname(__DIR__)) . '/includes/config.php';
require_once dirname(dirname(__DIR__)) . '/includes/data.php';
require_once dirname(dirname(__DIR__)) . '/includes/auth.php';
require_once dirname(dirname(__DIR__)) . '/includes/dashboard-layout.php';
require_login();

$id = $_GET['id'] ?? '';
$stapler = $id ? get_stapler($id) : null;
if (!$stapler) { header('Location: /dashboard/stapler/'); exit; }

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $brand = trim($_POST['brand'] ?? '');
    if ($title && $brand) {
        $stapler = array_merge($stapler, [
            'title'       => $title,
            'brand'       => $brand,
            'model'       => trim($_POST['model'] ?? ''),
            'driveType'   => $_POST['driveType'] ?? $stapler['driveType'],
            'category'    => $_POST['category'] ?? $stapler['category'],
            'status'      => $_POST['status'] ?? $stapler['status'],
            'price'       => (int)($_POST['price'] ?? 0),
            'priceOld'    => (int)($_POST['priceOld'] ?? 0) ?: null,
            'description' => trim($_POST['description'] ?? ''),
            'condition'   => trim($_POST['condition'] ?? ''),
            'specs'       => [
                'liftCapacity'    => trim($_POST['liftCapacity'] ?? '') ?: null,
                'liftHeight'      => trim($_POST['liftHeight'] ?? '') ?: null,
                'buildHeight'     => trim($_POST['buildHeight'] ?? '') ?: null,
                'yearBuilt'       => trim($_POST['yearBuilt'] ?? '') ?: null,
                'operatingHours'  => trim($_POST['operatingHours'] ?? '') ?: null,
                'weight'          => trim($_POST['weight'] ?? '') ?: null,
                'batteryInfo'     => trim($_POST['batteryInfo'] ?? '') ?: null,
                'tiresFront'      => trim($_POST['tiresFront'] ?? '') ?: null,
                'tiresRear'       => trim($_POST['tiresRear'] ?? '') ?: null,
                'specialEquipment'=> trim($_POST['specialEquipment'] ?? '') ?: null,
            ],
            'updatedAt'   => now_iso(),
        ]);

        // New uploads
        if (!empty($_FILES['images']['name'][0])) {
            $images = $stapler['images'] ?? [];
            foreach ($_FILES['images']['name'] as $i => $name) {
                if (!$name) continue;
                $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) continue;
                $fname = 'stapler_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES['images']['tmp_name'][$i], UPLOAD_DIR . '/' . $fname)) {
                    $images[] = UPLOAD_URL . '/' . $fname;
                }
            }
            $stapler['images'] = $images;
        }

        // Remove images
        if (!empty($_POST['remove_images'])) {
            $remove = $_POST['remove_images'];
            $stapler['images'] = array_values(array_filter(
                $stapler['images'], fn($img) => !in_array($img, $remove)
            ));
        }

        save_stapler($stapler);
        header('Location: /dashboard/stapler/');
        exit;
    } else {
        $error = 'Titel und Marke sind Pflichtfelder.';
    }
}

ob_start();
include __DIR__ . '/form.php';
$content = ob_get_clean();
dashboard_head('Stapler bearbeiten');
dashboard_layout('Stapler', $content);

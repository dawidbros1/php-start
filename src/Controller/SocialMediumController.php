<?php

declare (strict_types = 1);

namespace App\Controller;

use App\Helper\Request;
use App\Helper\Session;
use App\Model\SocialMedium;
use App\Repository\SocialMediumRepository;
use App\Rules\SocialMediumRules;

class SocialMediumController extends Controller
{
    public $style = "socialMedia";

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->requireAdmin();
        $this->repository = new SocialMediumRepository;
    }

    public function listAction()
    {
        $media = $this->repository->getAll();
        $data = $this->request->getParams(['name', 'link']);
        $this->view->render('socialMedium/list', ['media' => $media, "data" => $data ?? []]);
    }

    public function createAction()
    {
        $names = ['name', 'link'];
        $file = $this->request->file('icon') ?? null;

        if ($this->request->isPost() && $this->request->hasPostNames($names) && $file) {
            $data = $this->request->postParams($names);
            $path = self::$configuration['upload']['path']['social_media'];
            $rules = new SocialMediumRules();
            $medium = new SocialMedium($data);

            $postStatus = $medium->validate($data, $rules);
            $imageStatus = $medium->validateImage($file, $rules, 'icon');

            if ($postStatus && $imageStatus) {
                if ($this->uploadFile($path, $file)) {
                    $medium->icon = $path . $file['name'];
                    $this->repository->save($medium);
                    Session::set('success', 'Medium społecznościowe zostało dodane');
                    $this->redirect(self::$route['medium.list']);
                }
            }
            $this->redirect(self::$route['medium.list'], $data);
        }
    }

    public function editAction()
    {
        $names = ['name', 'link'];
        $medium = $this->getMedium();

        if ($this->request->isPost() && $this->request->hasPostNames($names)) {
            $upload = false;
            $rules = new SocialMediumRules();
            $path = self::$configuration['upload']['path']['social_media'];
            $file = $this->request->file('icon');

            $data = $this->request->postParams($names);
            $medium->update($data);

            if (!empty($file['name'])) {
                $upload = true;
                $imageValidateStatus = $medium->validateImage($file, $rules, 'icon');
            }

            if ($medium->validate($data, $rules)) {
                if ($upload == true) {
                    if ($imageValidateStatus == true) {
                        $uploadStatus = $this->uploadFile($path, $file);
                        $medium->icon = $path . $file['name'];
                    } else {
                        $uploadStatus = false;
                    }
                }

                if ($uploadStatus ?? true) {
                    $this->repository->save($medium);
                    Session::set('success', 'Dane zostały zaktualizowane');
                    $this->redirect(self::$route['medium.edit'], ['id' => $medium->id]);
                }
            }
        }

        $this->view->render('socialMedium/edit', ["medium" => $medium]);
    }

    public function deleteAction()
    {
        $medium = $this->getMedium();

        if ($this->request->isPost()) {
            // USUŃ
        }

        $this->view->render('socialMedium/delete', ["medium" => $medium]);
    }

    // ===== ===== ===== ===== =====

    private function getMedium()
    {
        $id = (int) $this->request->getParam('id');

        if (!$medium = $this->repository->get($id)) {
            Session::set('error', "Obiekt o wybranym ID nie istnieje");
            $this->redirect(self::$route['medium.list']);
        }

        return $medium;
    }
}

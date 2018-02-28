<?php
/**
 * Created by PhpStorm.
 * User: bintzandt
 * Date: 06/12/17
 * Time: 22:14
 */

class Home extends _SiteController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index(){
//        if (! $posts = $this->cache->file->get('posts')) {
//            $result = json_decode(file_get_contents('https://graph.facebook.com/hydrofiel/posts?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys'))->data;
//            $posts = [];
//            foreach ($result as $res) {
//                if (sizeof($posts) >= 5) break;
//                if (!isset($res->message)) continue;
//                if (!empty($media = json_decode(file_get_contents('https://graph.facebook.com/v2.11/' . $res->id . '/attachments?access_token=1787347374631170|MVXgVmEVfr8FjeOrCV_M-fP68Ys')))) {
//                    if (!isset($media->data[0])) continue;
//                    $post = $media->data[0];
//                    if (isset($post->media)) {
//                        $res->media = $post->media;
//                        $res->link = $post->target->url;
//                    }
//                }
//                unset($res->created_time);
//                array_push($posts, $res);
//
//                $this->cache->file->save('posts', $posts, 43200);
//            }
//        }
//        $data["posts"] = $posts;
        $data["posts"] = [];
        $data["events"] = $this->agenda_model->get_event(NULL, 3);
        $data['login'] = $this->session->userdata('logged_in');
        $data["verjaardagen"] = $this->profile_model->get_verjaardagen(3);
        $data["tekst"] = $this->page_model->get(1)->tekst;
        $this->loadView('templates/home', $data);
    }

    public function language(){
        if (isset($this->session->engels) && $this->session->engels) {
            $this->session->engels = FALSE;
        } else {
            $this->session->engels = TRUE;
        }
        redirect($this->agent->referrer());
    }
}
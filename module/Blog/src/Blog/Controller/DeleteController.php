<?php
 namespace Blog\Controller;

 use Blog\Service\PostServiceInterface;
 use Zend\Mvc\Controller\AbstractActionController;
 use Zend\View\Model\ViewModel;

 class DeleteController extends AbstractActionController
 {
     protected $postService;

     public function __construct(PostServiceInterface $postService)
     {
         $this->postService = $postService;
     }

     public function deletepostAction()
     {
         try {
             $post = $this->postService->findPost($this->params('blogid'));
         } catch (\InvalidArgumentException $e) {
             return $this->redirect()->toRoute('blog');
         }

         $request = $this->getRequest();

         if ($request->isPost()) {
             $del = $request->getPost('delete_confirmation', 'no');

             if ($del === 'yes') {
                 $this->postService->deletePost($post);
             }

             return $this->redirect()->toRoute('blog');
         }

         return new ViewModel(array(
             'post' => $post
         ));
     }
 }
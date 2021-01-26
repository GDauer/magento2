<?php


namespace Itatiaia\ProductTag\Controller\Adminhtml\Tag;


class Save extends \Magento\Backend\App\Action
{
    private $tagFactory;
    protected $messageManager;
    protected $resultRedirect;

    public function __construct
    (
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\RedirectFactory $resultRedirectFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Itatiaia\ProductTag\Model\TagFactory $tagFactory
    )
    {
        parent::__construct($context);
        $this->tagFactory = $tagFactory;
        $this->messageManager = $messageManager;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    public function execute()
    {
        $result = $this->resultRedirectFactory->create();

        try{
            $post = $this->_request->getPostValue();

            $tag = $this->tagFactory->create();

            if(isset($post['tag']['id'])){
                $tag->load($post['tag']['id']);
            }

            $tag->setTagName($post['tag']['tag_name']);
            $tag->setIsActive($post['tag']['is_active']);
            $tag->setPosition($post['tag']['position']);
            $tag->setImage($post['tag']['image']['0']['name']);
            $tag->setAttributeCode($post['tag']['attribute_code']);
            $tag->save();

            $this->messageManager->addSuccess(__('Dados cadastrados com sucesso'));

        } catch (\Exception $e){

            $this->messageManager->addError(__('Não foi possível salvar os dados. Por favor, tente novamente mais tarde.'));
        }

        $result->setPath('*/*/index');
        return $result;
    }
}

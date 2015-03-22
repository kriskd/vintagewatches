<?php
App::uses('WatchesController', 'Controller');
App::uses('CakeRequest', 'Network');
App::uses('ComponentCollection', 'Controller');
App::uses('SessionComponent', 'Controller/Component');
App::uses('Watch', 'Model');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');

class WatchesControllerTest extends ControllerTestCase {

	public $fixtures = array(
		'app.watch',
        'app.acquisition',
        'app.source',
		'app.order',
		'app.coupon',
		'app.brand',
		'app.payment',
		//'app.invoice',
		//'app.invoice_item',
		'app.address',
		//'app.detect',
		//'app.detects_order',
		'app.image',
		'app.state',
		'app.province',
		'app.country',
		'app.page',
		//'app.content'
        'app.cake_session',
	);

    public function setUp() {
        parent::setUp();
        $this->Watch = ClassRegistry::init('Watch');
        $this->ComponentCollection = new ComponentCollection();
        $this->Session = new SessionComponent($this->ComponentCollection);
        /*$this->Watches = $this->generate('Watches', array(
            'components' => array(
                'Auth' => array('user')
            )
        ));
        $this->Watches->Auth->staticExpects($this->any())
            ->method('user')
            ->will($this->returnValue(true));*/

    }

    public function testIndex() {
		$this->markTestIncomplete('testIndex not implemented.');
        $result = $this->testAction('/watches/index');
    }

    public function testOrder() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');

        $results = $this->testAction('/watches/order/1', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertNotEmpty($results['title']);
    }

/**
 * testAdminIndex method
 *
 * @return void
 */
	public function testAdminIndex() {
        $this->testAction('/admin/watches', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertCount(10, $this->vars['watches']);
	}

    public function testAdminIndexBrand() {
        $query = array(
            'brand_id' => '3',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(2, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(5, $ids);
        $this->assertContains(9, $ids);
    }

    public function testAdminIndexBrandAcquisition() {
        $query = array(
            'brand_id' => '3',
            'acquisition_id' => '2',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(2, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(5, $ids);
        $this->assertContains(9, $ids);
    }

    public function testAdminIndexSourceAcquisition() {
        $query = array(
            'source_id' => '1',
            'acquisition_id' => '2',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(1, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(5, $ids);
    }

    public function testAdminIndexUnsold() {
        $query = array(
            'sold' => '00',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(8, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(3, $ids);
        $this->assertContains(5, $ids);
        $this->assertContains(6, $ids);
        $this->assertContains(7, $ids);
        $this->assertContains(8, $ids);
        $this->assertContains(9, $ids);
        $this->assertContains(10, $ids);
        $this->assertContains(9999, $ids);
    }

    public function testAdminEdit() {
        $data = [
            'Watch' => [
                'id' => '7',
                'price' => '500',
                'repair_date' => date('Y-m-d'),
            ],
        ];
        $this->testAction('/admin/watches/edit/7', ['method' => 'post', 'data' => $data, 'return' => 'vars']);
        $this->assertContains('/admin/watches/view/7', $this->headers['Location']);
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => 7,
            ],
            'recursive' => -1
        ]);
        $this->assertEquals(500, $watch['Watch']['price']);
        $this->assertEquals(date('Y-m-d'), $watch['Watch']['repair_date']);
    }

    /**
     * Test admin edit of a sold watch
     * Changing price not allowed, repair_date can be changed
     */
    public function testAdminEditSold() {
        $data = [
            'Watch' => [
                'id' => '4',
                'price' => '500',
                'repair_date' => date('Y-m-d'),
            ],
        ];
        $this->testAction('/admin/watches/edit/4', ['method' => 'post', 'data' => $data, 'return' => 'vars']);
        $this->assertContains('/admin/watches/view/4', $this->headers['Location']);
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => 4,
            ],
            'recursive' => -1
        ]);
        $this->assertEquals(395, $watch['Watch']['price']);
        $this->assertEquals(date('Y-m-d'), $watch['Watch']['repair_date']);
    }

    public function testAdminDelete() {
        $id = 9999;
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'contain' => 'Image',
        ]);
        $folder = new Folder(WWW_ROOT.'files/'.$id);
        $original = new File(WWW_ROOT.$watch['Image'][0]['filename'], true, 0644);
        $large = new File(WWW_ROOT.$watch['Image'][0]['filenameLarge'], true, 0644);
        $medium = new File(WWW_ROOT.$watch['Image'][0]['filenameMedium'], true, 0644);
        $thumb = new File(WWW_ROOT.$watch['Image'][0]['filenameThumb'], true, 0644);
        $this->assertTrue($original->exists());
        $this->assertTrue($large->exists());
        $this->assertTrue($medium->exists());
        $this->assertTrue($thumb->exists());
        $this->testAction('/admin/watches/delete/'.$id, ['method' => 'post']);
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'recursive' => -1
        ]);
        $this->assertEmpty($watch);
        $this->assertFalse($original->exists());
        $this->assertFalse($large->exists());
        $this->assertFalse($medium->exists());
        $this->assertFalse($thumb->exists());
        $this->assertEmpty($folder->path);
    }

    public function testAdminDeleteSold() {
        $id = 9998;
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'contain' => 'Image',
        ]);
        $folder = new Folder(WWW_ROOT.'files/'.$id);
        $original = new File(WWW_ROOT.$watch['Image'][0]['filename'], true, 0644);
        $large = new File(WWW_ROOT.$watch['Image'][0]['filenameLarge'], true, 0644);
        $medium = new File(WWW_ROOT.$watch['Image'][0]['filenameMedium'], true, 0644);
        $thumb = new File(WWW_ROOT.$watch['Image'][0]['filenameThumb'], true, 0644);
        $this->assertTrue($original->exists());
        $this->assertTrue($large->exists());
        $this->assertTrue($medium->exists());
        $this->assertTrue($thumb->exists());
        $this->testAction('/admin/watches/delete/'.$id, ['method' => 'post']);
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'recursive' => -1
        ]);
        $this->testAction('/admin/watches/delete/'.$id, ['method' => 'post']);
        $this->assertNotEmpty($watch); 
        $this->assertNotEmpty($folder->path);
        $this->assertTrue($original->exists());
        $this->assertTrue($large->exists());
        $this->assertTrue($medium->exists());
        $this->assertTrue($thumb->exists());
        $original->delete();
        $large->delete();
        $medium->delete();
        $thumb->delete();
    }
}

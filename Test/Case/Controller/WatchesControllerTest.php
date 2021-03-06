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
        'app.owner',
        'app.source',
        'app.consignment',
        'app.purchase',
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
		'app.content',
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
        $this->testAction('/watches/index/longines');
        $this->assertCount(2, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(7, $ids);
        $this->assertContains(9999, $ids);
    }

    public function testOrder() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');

        $this->testAction('/watches/order/1', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertNotEmpty($this->vars['title']);
    }

    public function testOrderNoId() {
        $this->testAction('/watches/order', array(
            'method' => 'GET',
            'return' => 'vars',
        ));
        $this->assertContains('/pages/home', $this->headers['Location']);
    }

    public function testOrderNoEmailZip() {
        $this->Session->delete('Watch');
        $results = $this->testAction('/watches/order/1', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertContains('/orders', $this->headers['Location']);
    }

    public function testOrderBadId() {
        $this->Session->write('Watch.Order.email', 'PeterRHarris@teleworm.us');
        $this->Session->write('Watch.Address.postalCode', '61602');

        $results = $this->testAction('/watches/order/99', array(
            'method' => 'GET',
            'return' => 'vars',
        ));

        $this->assertContains('/orders', $this->headers['Location']);
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

    public function testAdminIndexBrandType() {
        $query = array(
            'brand_id' => '16',
            'type' => 'consignment',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(1, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(1, $ids);
    }

    public function testAdminIndexSourceType() {
        $query = array(
            'type' => 'purchase',
            'source_id' => '1',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertCount(1, $this->vars['watches']);
        $ids = Hash::extract($this->vars['watches'], '{n}.Watch.id');
        $this->assertContains(2, $ids);
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

    public function testAdminIndexNotFound() {
        $query = array(
            'brand_id' => '3',
            'page' => '2',
        );
        $url = Router::url(array('controller' => 'watches', 'action' => 'index', 'admin' => true, '?' => $query));
        $this->testAction($url, ['method' => 'get', 'return' => 'vars']);
        $this->assertContains('watches?brand_id=3&page=1', $this->headers['Location']);
    }

    public function testAdminView() {
        $this->testAction('/admin/watches/edit/3', ['method' => 'get', 'return' => 'vars']);
        $this->assertArrayNotHasKey('type', $this->vars['watch']['Watch']);
    }

    public function testAdminViewConsignment() {
        $this->testAction('/admin/watches/edit/1', ['method' => 'get', 'return' => 'vars']);
        $this->assertEquals('consignment', $this->vars['watch']['Watch']['type']);
    }

    public function testAdminViewPurchase() {
        $this->testAction('/admin/watches/edit/2', ['method' => 'get', 'return' => 'vars']);
        $this->assertEquals('purchase', $this->vars['watch']['Watch']['type']);
    }

    public function testAdminEdit() {
        $this->testAction('/admin/watches/edit/7', ['method' => 'get', 'return' => 'vars']);
        $this->assertFalse($this->vars['sold']);
        $this->assertEquals(295, $this->vars['watch']['Watch']['price']);
        $this->assertEquals('/files/7/longines5428side2.jpg', $this->vars['watch']['Image'][0]['filename']);
    }

    public function testAdminEditWatch() {
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

    public function testAdminEditInvalid() {
        $this->testAction('/admin/watches/edit/9997', ['method' => 'post']);
        $this->assertContains('/admin/watches', $this->headers['Location']);
    }

    public function testAdminEditChangeType() {
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'Watch.id' => 2
            ],
            'contain' => [
                'Purchase', 'Consignment',
            ],
        ]);
        $watch['Consignment'] = [
            'owner_id' => 1,
        ];
        $watch['Watch'] = [
            'type' => 'consignment',
        ];
        $this->testAction('/admin/watches/edit/2', ['method' => 'post', 'data' => $watch]);
        $watch = $this->Watch->findById(2);
        $this->assertEmpty($watch['Purchase']['source_id']);
        $this->assertEquals(1, $watch['Consignment']['owner_id']);
    }

    public function testAdminDelete() {
        $id = 9999;
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'contain' => 'Image',
        ]);
        $folder = new Folder(WWW_ROOT.'files/'.$id, true);
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
        $this->assertFalse(file_exists(WWW_ROOT.'files/'.$id));
    }

    public function testAdminDeleteSold() {
        $id = 9998;
        $watch = $this->Watch->find('first', [
            'conditions' => [
                'id' => $id,
            ],
            'contain' => 'Image',
        ]);
        $folder = new Folder(WWW_ROOT.'files/'.$id, true);
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
        $this->assertTrue(file_exists(WWW_ROOT.'files/'.$id));
        $this->assertTrue($original->exists());
        $this->assertTrue($large->exists());
        $this->assertTrue($medium->exists());
        $this->assertTrue($thumb->exists());
        $original->delete();
        $large->delete();
        $medium->delete();
        $thumb->delete();
    }

    /**
     * testRemove method
     *
     * @return void
     */
	public function testRemove() {
        $this->Session->write('Cart.watches', [3,5]);
        $this->testAction('/watches/remove/3');
        $this->assertEquals([5], $this->Session->read('Cart.watches'));
	}

    /**
    * @expectedException        NotFoundException
    * @expectedExceptionMessage Invalid watch
    */
    public function testRemoveException() {
        $this->Session->write('Cart.items', [3,5]);
        $result = $this->testAction('/watches/remove/11');
        $this->assertFalse($result);
    }
}

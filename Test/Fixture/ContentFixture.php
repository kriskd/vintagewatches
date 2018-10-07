<?php
/**
 * Content Fixture
 */
class ContentFixture extends CakeTestFixture {

    /**
     * Import
     *
     * @var array
     */
    public $import = array('connection' => 'development');

    /**
     * Fields
     *
     * @var array
     */
    public $fields = array(
        'id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => true, 'key' => 'primary'),
        'page_id' => array('type' => 'integer', 'null' => false, 'default' => null, 'unsigned' => false),
        'value' => array('type' => 'text', 'null' => true, 'default' => null, 'collate' => 'latin1_swedish_ci', 'charset' => 'latin1'),
        'indexes' => array(
            'PRIMARY' => array('column' => 'id', 'unique' => 1)
        ),
        'tableParameters' => array('charset' => 'latin1', 'collate' => 'latin1_swedish_ci', 'engine' => 'InnoDB')
    );

    /**
     * Records
     *
     * @var array
     */
    public $records = array(
        array(
            'id' => '1',
            'page_id' => '1',
            'value' => '<p><strong>April 15, 2015</strong></p>
            <p><strong>Two new watches added to the website</strong></p>

            <p>Dear friends and customers:</p>

            <p>I\'ve added two new watches to the website today: a beautifully restored Illinois "Ensign," plain bezel with seconds at 9:00; and a gorgeous Art Deco Ollendorf with double-hinged two tone (white and gold) case. To get right to the new watches, click <a href="https://brucesvintagewatches.com/watches">here</a>.</p><p><a href="http://vintagewatches.dev/items/add/1" class="btn btn-gold">Buy the book now.</a></p><p>Sincerely,</p>
            <p>Bruce Shawkey</p>'
        ),
        array(
            'id' => '2',
            'page_id' => '2',
            'value' => '<h2>General terms/conditions of sale/returns. Please read prior to ordering!</h2>


            <p><strong>How to order.</strong> To see the watches for sale, click on the link "Go to the Store" on the top of the web page. There, you will a synopsis of all the available watches for sale. Go over to the right column of any given watch, and click on the words "more details" to see a more detailed description of the watch, and all available photos. If you like the watch and want to buy it, click on the box that reads "Add to Cart," and a new window will appear showing the watch in your shopping basket. At that point, you may continue shopping, or check out. You should be aware that all watches are available until the moment someone checks out and pays for the watch. At that moment, the watch becomes unavailable. Therefore, there is a slim (but nevertheless possible) chance that the watch in your shopping basket may become unavailable if someone beats you to it and checks out first. So if there is a watch that you <strong>really</strong> want, I suggest you check out with that watch first, then continue shopping. </p>

            <p><strong>Terms of sale.</strong> All watches are one-of-a-kind, and sold first-come, first-served. Generally speaking, I do not "hold" watches, except under extraordinary circumstances. If you want a certain watch, <strong>buy the darned thing!</strong> If you have watches to trade, that\'s fine. But we\'ll negotiate that later as a separate transaction. In the meantime, lock in your purchase to avoid disappointment. You may feel free to inquire about any watch you wish, but the watch remains "in play" and available to the first customer who makes a confirmed purchase</p>

            <p><strong>General condition/grading of watches</strong> Watch cases described as "excellent" are pleasing to the eye and will probably satisfy the great majority of collectors. If gold filled, most of the gold layer is intact. May have minor brassing on the high spots. May have a very minor amount of splitting on the underside. If condition is worse than that, it will be noted. If you receive a watch, and it is less than acceptable to you because of the case condition, you are covered under the 7-day return privilege.</p>
            <a name="warranty"></a>
            <a></a><p><strong>Mechanical condition of watch.</strong> Generally speaking, when I receive a watch and it will run continuously within 2 min/day plus or minus (5 minutes on watches before the year 1930), I will NOT have them cleaned/oiled/adjusted. Unless a certain watch is otherwise noted in the description as having been serviced, I sell my watches "as received." Like all things mechanical, these old watches require periodic servicing, especially if you require exact timing. Most collectors are a little "forgiving" on the accuracy of a vintage timepiece. But if you are a stickler for precision, I think you will find that buying from me is a "hit-and-miss" proposition. You may feel free to ask me to time a particular watch for you, but again the watch remains "in play" and available to the first confirmed sale. Watches that have been serviced by my watchmaker will be guaranteed to run and keep time for six months from date of purchase. * Watches not serviced are sold <strong>AS-IS</strong>, and carry no further warranty beyond the 7-day return privilege described in the "<strong>Returns</strong>" paragraph below.</p>

            <p>* Six month warranty covers defects from NORMAL WEAR, and does not include damage caused by dropping or immersion in water/liquids. Broken or cracked crystals are also not covered, unless the crystal was defective when you received the watch.</p>

            <p><strong>Returns</strong>  Any watch may be returned for any reason for up to seven days after you receive the watch. You should send me an email as a courtesy to let me know the watch is being returned. The watch must be returned to me in the same condition received. You should insure the watch for the full purchase price against loss/damage in the mail. Upon receipt and inspection of the watch, prompt refund will be given in the form of a credit back to the debit/credit card used for the purchase. Except in situations where the watch is defective (i.e., nonrunning) or is significanty different than described, return shipping will be at the buyer\'s expense.</p>

            <p>After seven days, watches may not be returned for any reason. You may certainly "trade in" the watch, or sell it back to me at some future date, but it will generally be a lower price than you paid for it.</p>

            <p>Any other questions, please email me!</p>'
        ),
        array(
            'id' => '3',
            'page_id' => '3',
            'value' => '<h2>Selling or trading in your watches</h2>

            <p>

            </p><h3>Introduction</h3>
            <p>Whether you\'re looking to trade in your watches for another watch of mine that you wish to buy, or looking to sell outright, I\'m always looking to acquire watches for my inventory. I feel I am very competitive with my offers, and right up there with some of the so-called "big players" in the watch business. Just because a high profile watch dealer <strong>charges</strong> more for a watch doesn\'t mean he will necessarily <strong>pay</strong> you any more for a certain watch than I would. In fact, if anything, he will pay you less. That\'s because the "big players" most always have "big overhead" in terms of store rent, advertising, payroll, etc., and they need to make a large margin between their sell and buy price. Being I am a one-person operation, I have virtually none of those expenses, and can therefore offer you a better price.</p>

            <h3>Buy versus consign</h3><p>With a few exceptions (noted in just a moment) I do not consign watches. Generally speaking, I\'d rather just make you a fair offer for an outright purchase, and be done with it. The exceptions are either high-priced watches, or watches that appeal to a very specialized buyer (the two usually go hand in hand, but not always!). So if you have something expensive and/or "special" in some way, I may talk to you about the possibility of a consignment arrangement.</p>

            <h3>Inspection</h3>
            <p>Whether you\'re selling me one watch, or an entire collection, I need to inspect the watch(es) prior to making an offer. No exceptions. In most cases, this can be accomplished by simply shipping me your watches. However, I will travel to inspect and make an offer on larger accumulations/collections. Please contact me for more info on this.</p>

            <h3>Photos/Images</h3>
            <p>In conjunction with the "Inspection" paragraph above, it always helps to see images of your watches before you send them. That way, I can get a better idea of condition, and perhaps even give you a rough estimate of what I can offer. Please keep your image sizes reasonable, say no more than 200KB per individual image, and your total file attachments of ALL photos below 2MB. And for heaven\'s sake, send me clear photos! A blurry photo is no photo! If you don\'t have at least a rudimentary understanding of digital photography and how to size your photographs to a reasonable size for email attachment, probably best that you not try to send photographs, but rather just a written description.</p> 

            <h3>What I\'m looking for</h3>
            <p>The watches on this website are a good indication. I will consider darned near anything, but in particular I\'m looking for better names/grades of watches like Omega, Longines, Ulysse Nardin, LeCoultre, IWC, chronographs, etc. Also, the scarcer Hamiltons and Gruens (like the Quadrons, longer Curvexes, etc.). In general, I am not buying common brand solid gold watches right now because of the high price of gold and the fact that the "collector mentality" has not caught up to the price of gold on these pieces. I am, however, interested in solid gold watches that are "rare" in and of themselves, disregarding the fact they are gold. Examples would be a solid gold vintage Breitling chronograph. Or a Hamilton Flight I. Or a Gruen Curvex 52mm "Majesty" in 14kt solid gold. You get the idea.</p>
            <p><strong>Generally speaking, I am not looking for parts and tools.</strong> I am more interested in complete watches. But there are always exceptions. I\'m always interested in parts/partials for rare movement calibers. And I will consider parts and tools if they are part of a "complete buyout" of a store or trade shop where are a good amount of complete watches as part of the deal. When in doubt, ask.</p>

            <h3>Pricing</h3>
            <p>When you send me your watches, you can either set a minimum sell price, or simply let me make you an offer. This is a matter of personal preference, and there is no right or wrong way to do this. If you set a minimum price, I will do my best to meet it or in some cases even beat it!</p> 

            <h3>All or nothing versus "cherry picking"</h3>
            <p>Most collectors who send me a group of watches want me to buy the whole lot. But if you\'ll allow me to "pick and choose," let me know, and I can generally offer you a higher price. I\'m always agreeable to the "all or nothing" approach, but understand that if there are some common watches in the batch, that this will be reflected in the overall offer. This is simply the way it goes. But I think you\'ll find my offers are fair. People who have "shopped me" before usually end up coming back!</p>


            <p></p><h2>Any other questions, please email me, and let\'s talk!</h2>
            '
        ),
        array(
            'id' => '4',
            'page_id' => '4',
            'value' => 'Hello. My name is Bruce Shawkey, and I\'ve been collecting vintage watches since 1986, and have been a dedicated dealer in vintage watches since 1991 when I left my last "real" job as a technical writer for a national trade association. Starting out with crude paper catalogs, I soon jumped onto the Internet in 1996 with thewatchstore". I sold that domain name in 2012, and am now operating under the name of brucesvintagewatches.<br /><br />All during this time, my mission has remained the same: To provide nice, affordable watches to collectors who don\'t necessarily have a bundle of money to spend. I pride myself in pricing my watches realistically, which means I\'m extremely competitive with others on the \'Net who price their merchandise at close to retail, in the hopes of snagging the occasional "sucker" who comes along. I\'m more interested in building long-term and trusting relationships with my customers, which I accomplish not only with fair pricing, but providing full disclosure about my watches, along with a little educational information about each watch that I sell.<br /><br />It must be working, because many of my customers have been with me since the beginning! And new customers keep arriving, most often by word of mouth, which is the best advertising there is!<br /><br />I\'m also very interested in the history of these old watches and the companies that made them. To that end, I\'m very active with the National Association of Watch &amp; Clock Collectors. I\'ve written many articles for their educational journal over the years, and am a frequent visitor to the Library and Research Center at their headquarters in Columbia, Pa. I\'ve also published articles in various other watch magazines for consumers.<br /><br />There\'s an old saying about "Do what you love, and you\'ll never work another day in your life." I\'ve been very fortunate in finding out what it is that I love to do. And I plan to keep on doing it as long as there are people out there who appreciate the beauty and functionality of these great old watches.<br /><br />May you always find joy in wearing a vintage wristwatch. May it always remind you that time is a gift, never to be squandered.<br /><br /><br />Bruce Shawkey'
        ),
        array(
            'id' => '5',
            'page_id' => '5',
            'value' => '<p>This page is kind of the heart-and-soul of the website. Here is where I showcase the watches I have for sale at any given moment, and is probably the page where you\'ll spend the most time. Each "frame" below presents a small photo and a mini-description of each watch for sale. If you know you want the watch right away, click the box titled "Add to Cart." To find out more about any given watch, click on either the highlighted text "More details" at the end of the mini-description, or on the small picture to the left. You\'ll be taken to a page containing the rest of the description, along with more and larger photos of the particular watch. From there, you can either add the watch to your shopping cart, or return to this page to continue viewing more watches.</p><p>When someone buys a watch, it disappears from this page. So if you visit this page a few days (or a few hours) later, and a watch that you looked at is no longer there, it\'s because someone bought it. <span style="font-weight:700;">Watches remain "in play"</span> to all viewers of this web page until someone "checks out" and pays for the watch. So there is a small (but nevertheless possible) chance that a watch sitting your shopping cart might not be there when you check out if someone has beaten you to it. Therefore, if there is a watch that you really REALLY want, I would urge you to "check out" as quickly as possible, and then continue shopping if there are other watches of milder interest. Happy shopping!</p>'
        ),
        array(
            'id' => '6',
            'page_id' => '6',
            'value' => '<p>This page is where you find all the watches I have sold since the beginning of this website in 2013. This page automatically updates whenever a watch is sold, so the watches are listed chronologically, from most recent watch sold to the earliest watches sold. If you can\'t find a certain watch in the store that I\'ve just announced, it\'s probably because somebody beat you to it, and you\'ll find it here.</p><p>Unlike my competitors, I leave the prices on the watches so you can see what they bought and to see how I stack up against my competition on prices. You can even perform a little market research to see what your own watches are worth. I hope you find this latest function of my website useful!</p>'
        ),
    );

}

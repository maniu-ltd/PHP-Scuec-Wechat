<?php
/**
 * Created by PhpStorm.
 * User: YiWan
 * Date: 2018/6/16
 * Time: 19:27
 */

namespace App\Http\MessageHandler;

use App\Http\Service\HelperService;
use App\Http\Service\KuaiDiApiService;
use App\Http\Service\OuterApiService;
use EasyWeChat\Kernel\Contracts\EventHandlerInterface;
use EasyWeChat\Kernel\Messages\News;
use EasyWeChat\Kernel\Messages\NewsItem;

class TextMessageHandler implements EventHandlerInterface
{
    public function handle($message = null)
    {
        $keyword = trim($message['Content']);
        $switchKey = $this->dealStr($keyword);

        switch ($switchKey) {
            case 'help':
                $content = $this->helpStr();     //帮助信息文本
                return $content;  //标准格式回复
                break;
            case 'weather':
                return OuterApiService::weather();
                break;
            case 'bus':
                $content = "<a href=\"http://m.amap.com/search/view/keywords=".$keyword."\">〖高德地图-".$keyword."〗</a>";//暂未找到免费公交查询接口,暂时跳转到高德地图查看
                return $content;
                break;
            case 'train':
                $trainNum = HelperService::getContent($keyword, "火车");   // 得到车次
                return OuterApiService::train($trainNum);
                break;
            case 'kuaidi':
                $kuaidi_num = HelperService::getContent($keyword, "快递");  // 得到快递单号
                $kuaidi_service =new KuaiDiApiService($kuaidi_num);
                $content = $kuaidi_service->kuaiDi();
                return $content;
                break;
            case 'map':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '中南民族大学地图',
                            'description' => '点击进入民大地图\n周边搜索功能，囊括餐饮、娱乐、购物等周边生活信息，轻松掌控城市生活。\n线路搜索功能，轻松规划出行线路',
                            'url' => 'https://m.expoon.com/qjjx/xuexiao/7onuyhthawb.html',
                            'image' => config('app.base_url').'/img/baidumap.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'translate':
                $word = HelperService::getContent($keyword, "翻译");   // 获得需要翻译的文本
                return OuterApiService::translate($word);
                break;
            case 'fuxiu':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '纯干货 | 十校联盟辅修事项',
                            'description' => '小塔为你整理的辅修干货在这里呀，希望可以帮到要辅修的同学哦~',
                            'url' => 'http://mp.weixin.qq.com/s/xYNhsFdgdWlxP4b_lZd_JQ',
                            'image' => config('app.base_url').'/img/fuxiu.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'zhuxue':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '干货 | 在大学一定要多拿几个奖',
                            'description' => '奖/助学金大汇总，奖项多到你想不到！',
                            'url' => 'http://mp.weixin.qq.com/s/vxHhH_mntbbcRioBSbor2A',
                            'image' => config('app.base_url').'/img/zhuxue.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'SchoolCalendar':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '时刻表 | 民大生存必备，你要的时间都在这里！',
                            'description' => '时刻表每学期一更，维持民大最新的各地点记录～',
                            'url' => 'http://mp.weixin.qq.com/s/76TUgsjaqb-H0Ep4iMwqmA',
                            'image' => config('app.base_url').'/img/Calendar.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'xuyuanqiang':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '许愿墙',
                            'description' => '这里是资讯民大许愿墙。\n你和小塔，只差一个心愿。',
                            'url' => 'http://wish.stuzone.com/',
                            'image' => 'http://ww1.sinaimg.cn/large/98d2e36bjw1eruqmaxcnwj20go099ad3.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'putonghua':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '干货丨普通话等级考试报名攻略',
                            'description' => '小塔整理的普通话考试干货，内容有不完整的地方欢迎来补充哦~希望这个干货能帮到各位塔粉~',
                            'url' => 'https://mp.weixin.qq.com/s/wRz-ztt0OI9oKtKKubhIxw',
                            'image' => config('app.base_url').'/img/putonghua.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'teacher':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '教师资格证考试建议',
                            'description' => '湖北地区是国家统考地区，同样属于统考地区的还有河北，山东，山西，贵州，浙江，海南，安徽，上海，广西。',
                            'url' => config('app.blog_url').'/teacher-certification.html',
                            'image' => config('app.base_url').'/img/teacher.jpg',
                        ]
                    )
                ];
                return new News($items);
                break;
            case 'minren':
                $content = '《民人志》往期目录：'.config('app.blog_url').'/category/minrenzhi';
                return $content;
                break;
            case 'xinli':
                $content = '《心理咨询》往期目录：'.config('app.blog_url').'/category/xinlizixun';
                return $content;
                break;
            case 'studyroom':
                $tousername = $message['FromUserName'];
                $content = '<a href="https://wechat.stuzone.com/iscuecer/lab_query/web/studyroom?openid='.
                    $tousername.'">自习室查询</a>';
                return $content;
                break;
            case 'campus_network':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '小塔的校园网使用说明书',
                            'description' => '校园网的秘密都在这里了~',
                            'url'       => 'http://mp.weixin.qq.com/s?__biz=MzA5OTA0ODUyOA==&mid=400024069'.
                                '&idx=1&sn=58c58c242113fdeeb86ea575ac997d7d&scene=4#wechat_redirect',
                            'image' => config('app.base_url').'/img/wifi.jpg',
                        ]
                    ),
                    new NewsItem(
                        [
                            'title'       => '【新技能】用路由器共享校园网',
                            'description' => '小塔教你如何在宿舍使用路由器共享校园网~',
                            'url'       => 'http://mp.weixin.qq.com/s?__biz=MzA5OTA0ODUyOA==&mid=211654347&idx=1'.
                                '&sn=b44754a9f238824bbc2b117d0e915b33',
                        ]
                    )
                ];
                return new News($items);
            case 'ruxue':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '中南民族大学2014年新生入学须知',
                            'url'       => config('app.blog_url').'/2014-freshmen-notice.html',
                            'image' => 'http://ww1.sinaimg.cn/mw690/98d2e36bgw1ejckhujt7aj20ci08cdgv.jpg',
                        ]
                    ),
                    new NewsItem(
                        [
                            'title'       => '中南民族大学2014年新生入学户口迁移须知',
                            'url'       => config('app.blog_url').'/blog/2014-freshmen-account-migration.html',
                            'image' => 'http://ww4.sinaimg.cn/mw690/98d2e36bgw1ejckhuzx38j207y07xmxe.jpg',
                        ]
                    ),
                    new NewsItem(
                        [
                            'title'       => '新生入学谨防盗抢诈骗提示',
                            'url'       => config('app.blog_url').'/freshman-fangpian-warn.html',
                            'image' => 'http://ww4.sinaimg.cn/mw690/98d2e36bgw1ejckhuzx38j207y07xmxe.jpg',
                        ]
                    ),
                    new NewsItem(
                        [
                            'title'       => '中南民族大学2014年新生学费、住宿费收费标准',
                            'url'       => config('app.blog_url').'/2014-freshman-fees.html',
                            'image' => 'http://ww4.sinaimg.cn/mw690/98d2e36bgw1ejckhuzx38j207y07xmxe.jpg',
                        ]
                    )
                ];
                return new News($items);
            case 'rebinding':

                break;
            case '课表':
                $items = [
                    new NewsItem(
                        [
                            'title'       => '收到你的图片了噢',
                            'description' => '我们会尽快处理',
                            'url'       => 'www.baidu.com',
                        ]
                    )
                ];
                return new News($items);

            case 'test2':
                return 'test2√';
            default:
                return $message['Content'].$message['FromUserName'];

        }
    }

    /**
     * 辅助函数
     * @param $keyword
     * @return string
     */

    private function dealStr($keyword) //字符串处理，用于确定用户的目的，正则匹配增加容错率
    {
        if (($keyword == '0') or ($keyword == '帮助')) { //此处有陷阱，如果字符串以合法的数字开头，就用该数字作为其值，否则其值为数字0。
            return 'help';
        } elseif ($keyword == '天气') {
            return 'weather';
        } elseif (preg_match("/^公交|^地铁/u", $keyword)) {
            return 'bus';
        } elseif (preg_match("/^火车/u", $keyword)) {
            return 'train';
        } elseif (preg_match("/^快递/u", $keyword)) {
            return 'kuaidi';
        } elseif ($keyword == '地图') {
            return 'map';
        } elseif (preg_match("/^翻译/u", $keyword)) {
            return 'translate';
        } elseif (($keyword == '辅修') or ($keyword == '双学位')) {
            return 'fuxiu';
        } elseif (($keyword == '奖学金') or ($keyword == '助学金')) {
            return 'zhuxue';
        } elseif ($keyword == '时刻表' || $keyword == '校历' || $keyword ==  '时间表') {
            return 'SchoolCalendar';
        } elseif (strpos($keyword, '许愿') !== false || $keyword == '心愿墙' || $keyword == '表白墙') {
            return 'xuyuanqiang';
        } elseif (($keyword == '普通话') or (strpos($keyword, '普通话考试') !== false)) {
            return 'putonghua';
        } elseif (($keyword == '教师证') or (strpos($keyword, '教师资格证') !== false)) {
            return 'teacher';
        } elseif (($keyword == '民人志') or ($keyword == '名人志')) {
            return 'minren';
        } elseif ($keyword == '心理咨询') {
            return 'xinli';
        } elseif (($keyword == '自习室') or ($keyword == '自习')) {
            return 'studyroom';
        } elseif (strpos($keyword, '校园网') !== false) {
            return 'campus_network';
        } elseif (($keyword == '重新绑定') OR ($keyword == '绑定账号') OR ($keyword == '账号绑定')){
            return 'rebinding';
        }
    }




    //帮助信息文本。注意：下面的文字顶格换行微信里面显示也是这样
    private function helpStr()
    {
        $helpStr = "资讯民大功能菜单，回复括号里的关键词，get√
生活查询 :
".HelperService::getEmoji("\ue04A")."【天气】 ".HelperService::getEmoji("\ue112")."【快递】
".HelperService::getEmoji("\ue009")."【电话】 ".HelperService::getEmoji("\ue201")."【地图】
".HelperService::getEmoji("\ue159")."【公交/地铁】
".HelperService::getEmoji("\ue00C")."【电视直播】
".HelperService::getEmoji("\ue01F")."【火车】
学习查询 :
".HelperService::getEmoji("\ue157")."【课表】 ".HelperService::getEmoji("\ue44C")."【校历】
".HelperService::getEmoji("\ue02B")."【考试】 ".HelperService::getEmoji("\ue14E")."【成绩】
".HelperService::getEmoji("\ue345")."【翻译】 ".HelperService::getEmoji("\ue114")."【图书】
".HelperService::getEmoji("\ue148")."【当前借阅】".HelperService::getEmoji("\ue157")."【大物实验】
".HelperService::getEmoji("\ue301")."【时刻表】

信息资讯 :
".HelperService::getEmoji("\ue534")."【辅修】".HelperService::getEmoji("\ue114"). "【助学金】
".HelperService::getEmoji("\ue302")."【教师证】".HelperService::getEmoji("\ue157")."【医保】
".HelperService::getEmoji("\ue114")."【号内搜】".HelperService::getEmoji("\ue532")."【考证】

其它 :
".HelperService::getEmoji("\ue428")."【微社区】
".HelperService::getEmoji("\ue24e")."【关于】 ".HelperService::getEmoji("\ue327")."【帮推】
".HelperService::getEmoji("\ue022")."【帮助】 ".HelperService::getEmoji("\ue103")."【反馈】
".HelperService::getEmoji("\ue443")."【重新绑定】
".HelperService::getEmoji("\ue019")."【历史消息】
更多功能努力研发ing";
        return $helpStr;
    }
}
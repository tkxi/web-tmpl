/**
 * User Agent
 */
const ua = navigator.userAgent.toLowerCase();
const isiPhone = (ua.indexOf('iphone') > -1) && (ua.indexOf('ipad') == -1);
const isAndroid = (ua.indexOf('android') > -1) && (ua.indexOf('mobile') > -1);
const isSP = (isiPhone || isAndroid);

/**
 * URLパラメータ取得＆配列格納
 *
 * @returns Array
 */
function getLocSearch() {
  let ary = {};
  let pair = location.search.substring(1).split('&');
  for (let i = 0; pair[i]; i++) {
    let kv = pair[i].split('=');
    ary[kv[0]] = kv[1];
  }
  return ary;
}

/**
 * アドレスにパラメータ追加
 *
 * @param {*} url site root path
 * @param {*} obj 連想配列
 */
function setLocSearch(url, obj) {
  let str = '';
  for (let key in obj) {
    str += key + '=' + obj[key] + '&';
  }
  str = str.slice(0, -1);
  history.replaceState(null, null, url + '?' + str);
}

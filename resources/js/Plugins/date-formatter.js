import moment from "moment";

/**
 * Returns calendar date from the timestamp provided
 * (ex: Saturday, February 20th 2:00 PM)
 *
 * @param {timestamp} timestamp to convert
 * @return moment conversion of timestamp to calendar date
 */
export function calendar(timestamp){
  return moment(timestamp).local().format('LLL');
}

/**
 * Returns time elapsed from now to the timestamp provided
 * (ex: 4 hours ago, 6 days ago)
 *
 * @param {timestamp} timestamp to convert
 * @return moment conversion of time elapsed since the timestamp
 */
export function fromNow(timestamp){
  return moment(timestamp).local().fromNow();
}

export default {
  install(Vue, options) {

    Vue.prototype.$calendar = calendar;

    Vue.prototype.$fromNow = fromNow;
  }
}

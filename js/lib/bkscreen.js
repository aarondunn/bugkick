//												BKScreen
function BKScreen(options) {
	this.options = {
		blockerID: 'ajaxLoading'
	};
	this._init(options);
}
BKScreen.State={
	Blocked:1,
	Released:0
};
BKScreen.prototype._init = function(options) {
	if(options !== undefined)
		this.extend(this.options, options);
	this.blocker=document.getElementById(this.options.blockerID);
	this.state=BKScreen.State.Released;
};
BKScreen.prototype.extend = function(dest, src) {
	for(var prop in src)
		dest[prop] = src[prop];
};
BKScreen.prototype.block = function() {
	this.blocker.style.display='block';
	this.state=BKScreen.State.Blocked;
	return this;
};
BKScreen.prototype.release = function() {
	this.blocker.style.display='none';
	this.state=BKScreen.State.Released;
	return this;
};
BKScreen.prototype.getState = function() {
	return this.blocker.style.display=='none'
		? BKScreen.State.Released
		: BKScreen.State.Blocked;
};
//												BKScreen	END
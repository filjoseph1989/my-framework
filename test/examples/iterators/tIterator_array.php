<?php
# - Here is an implementation of the Iterator interface for arrays
#     which works with maps (key/value pairs)
#     as well as traditional arrays
#     (contiguous monotonically increasing indexes).
#   Though it pretty much does what an array
#     would normally do within foreach() loops,
#     this class may be useful for using arrays
#     with code that generically/only supports the
#     Iterator interface.
#  Another use of this class is to simply provide
#     object methods with tightly controlling iteration of arrays.

class tIterator_array implements Iterator {
    private $myArray;

    public function __construct( $givenArray ) {
        $this->myArray = $givenArray;
    }
    function rewind() {
        return reset($this->myArray);
    }
    function current() {
        return current($this->myArray);
    }
    function key() {
        return key($this->myArray);
    }
    function next() {
        return next($this->myArray);
    }
    function valid() {
        return key($this->myArray) !== null;
    }
}

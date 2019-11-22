//var token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiI1NWMwNjRjZDY5OTUxZWYwMjIyZTFiMmEiLCJpYXQiOjE0NDIwMzM0MjksImV4cCI6MTQ0MjI0OTQyOX0.mu_yrc5havxaiNhJRrjuuJfGy_swQBRGJJqrACA6BfI";
var token = "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9zYnRqYXBhbi5jb20iLCJhdWQiOiJodHRwOlwvXC9leGFtcGxlLmNvbS5waHQiLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiZGF0YSI6eyJleHRlbnNpb24iOiI2MzM2IiwibmFtZSI6IlJvZ21lciBCdWxhY2xhYyIsInBvc2l0aW9uIjoiIn19.8uVxdEbyEPKkvFE9iqB57A7hEycESf6F-44DIydm4bg"
function decodeToken(token){
	var playload = JSON.parse(atob(token.split('.')[1]));
    console.log(playload);
    
};

decodeToken(token);
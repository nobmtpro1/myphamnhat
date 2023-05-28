// var searchForm = document.querySelector("#my-search-form form");

// const input = document.createElement("input");
// input.setAttribute("name", "post_type");
// input.setAttribute("value", "product");
// input.setAttribute("type", "hidden");
// searchForm.appendChild(input);

var myAddToCart = document.querySelectorAll(".my-add-to-cart");
var myAddToWishlist = document.querySelectorAll(".my-add-to-wishlist");

myAddToCart?.forEach((e) => {
  e?.addEventListener("click", function () {
    const addToCartButton = e?.parentElement?.querySelector(
      ".add_to_cart_button"
    );
    console.log(addToCartButton);
    addToCartButton?.click();
  });
});

myAddToWishlist?.forEach((e) => {
  e?.addEventListener("click", function () {
    const addToWishlistButton = e?.parentElement?.querySelector(
      ".yith-wcwl-add-to-wishlist .add_to_wishlist"
    );
    console.log(addToWishlistButton);
    addToWishlistButton?.click();
  });
});

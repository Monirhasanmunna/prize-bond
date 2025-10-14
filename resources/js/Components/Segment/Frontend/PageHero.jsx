import React from "react";

const PageHero = ({ banner_text, banner_img }) => {
    return (
        <div className="">
            <h2>images</h2>
            <img src={banner_img} alt="banner-img" />
        </div>
    );
};

export default PageHero;

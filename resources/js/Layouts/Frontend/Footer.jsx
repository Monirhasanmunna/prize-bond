import React from 'react'
import {
    FaFacebookF,
    FaInstagram,
    FaXTwitter,
    FaLinkedinIn,
    FaPinterestP,
} from 'react-icons/fa6'
import {FiMail, FiPhone} from "react-icons/fi";
import {FaSkype} from "react-icons/fa";
import {Link} from "@inertiajs/react";

const Footer = ({contact_info = null, social_links = null, copyright = null}) => {
    return (
        <footer className="bg-[#111111] text-gray-300 py-8">
            <div className="container mx-auto px-4">
                <div className="grid grid-cols-1 md:grid-cols-3 gap-8 md:text-center md:text-left">

                    {/* Quick Links */}
                    <div className="w-full flex md:justify-center">
                        <div>
                            <h3 className="font-semibold mb-4 text-white">Quick Links</h3>
                            <ul className="space-y-2">
                                <li>
                                    <Link href={route('about_company')}
                                        className="flex items-center md:justify-start gap-2 hover:text-red-500 transition"
                                    >
                                        <span className="w-2 h-2 bg-red-600 rounded-full"></span>
                                        About My Company
                                    </Link>
                                </li>
                                <li>
                                    <Link href={route('about_me')}
                                          className="flex items-center md:justify-start gap-2 hover:text-red-500 transition"
                                    >
                                        <span className="w-2 h-2 bg-red-600 rounded-full"></span>
                                        About Me
                                    </Link>
                                </li>
                                <li>
                                    <Link href={route('sample-script')}
                                          className="flex items-center md:justify-start gap-2 hover:text-red-500 transition"
                                    >
                                        <span className="w-2 h-2 bg-red-600 rounded-full"></span>
                                        Sample Scripts
                                    </Link>
                                </li>
                                <li>
                                    <Link href={route('contact_details')}
                                          className="flex items-center md:justify-start gap-2 hover:text-red-500 transition"
                                    >
                                        <span className="w-2 h-2 bg-red-600 rounded-full"></span>
                                        Contact Details
                                    </Link>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Connects */}
                    <div className="w-full flex md:justify-center">
                        <div>
                            <h3 className="font-semibold mb-4 text-white">Connects</h3>
                            <ul className="space-y-3">
                                <li className="flex items-center md:justify-center md:justify-start gap-2">
                                    <FiMail className="w-5 h-5 text-red-600" />
                                    <a href={`mailto:${contact_info?.email}`} className="hover:text-red-500 transition">
                                        {contact_info?.email}
                                    </a>
                                </li>
                                <li className="flex items-center md:justify-center md:justify-start gap-2">
                                    <FiPhone className="w-5 h-5 text-red-600" />
                                    <a href={`tel:${contact_info?.phone}`} className="hover:text-red-500 transition">
                                        {contact_info?.phone}
                                    </a>
                                </li>
                                <li className="flex items-center md:justify-center md:justify-start gap-2">
                                    <FaSkype className="w-5 h-5 text-red-600" />
                                    <span>{contact_info?.skype}</span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    {/* Social Media */}
                    <div className="w-full flex md:justify-center">
                        <div>
                            <h3 className="font-semibold mb-4 text-white">Follow Us On</h3>
                            <div className="flex justify-center md:justify-start gap-4">
                                <a href={social_links?.linkedin} className="bg-white rounded-full p-2 shadow hover:scale-110 transition">
                                    <FaLinkedinIn className="w-5 h-5 text-blue-600" />
                                </a>
                                <a href={social_links?.facebook} className="bg-white rounded-full p-2 shadow hover:scale-110 transition">
                                    <FaFacebookF className="w-5 h-5 text-blue-500" />
                                </a>
                                <a href={social_links?.instagram} className="bg-white rounded-full p-2 shadow hover:scale-110 transition">
                                    <FaInstagram className="w-5 h-5 text-pink-600" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Divider */}
                <div className="border-t border-gray-700 mt-8 pt-4 text-center text-sm text-gray-400">
                    {copyright?.content}
                </div>
            </div>
        </footer>
    )
}

export default Footer

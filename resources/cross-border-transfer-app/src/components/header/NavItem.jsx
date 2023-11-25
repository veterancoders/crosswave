import { NavLink } from "react-router-dom";

const NavItem = ({ style, link, pageTitle }) => {
    return (
        <li className={style}>
            <NavLink
                to={link === "/home" ? "/" : link}
                className={({ isActive }) =>
                    (isActive ? "border-[#FFCD29] border-b-2 pb-1" : "") +
                    " capitalize"
                }>
                {pageTitle}
            </NavLink>
        </li>
    );
};

export default NavItem;

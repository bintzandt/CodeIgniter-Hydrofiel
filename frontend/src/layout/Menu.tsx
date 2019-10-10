import React, { useState } from "react";
import styled from "styled-components";
import BurgerMenu from "react-burger-menu/lib/menus/slide";
import { Link } from "react-router-dom";

interface MenuItemProps {
	key: number;
	to: string;
	name: string;
	icon?: string;
}

interface DropdownMenuItemProps extends MenuItemProps {
	children?: MenuItemProps[];
}

const items = {
	home: "fa fa-home",
	zwemmen: "fa fa-swimmer",
	waterpolo: "fa fa-volleyball-ball",
	vereniging: "fa fa-users",
	contact: "fa fa-phone",
	calendar: "fa fa-calendar",
};

const menuItems: DropdownMenuItemProps[] = [
	{ key: 2, icon: items.home, name: "Home", to: "/" },
	{ key: 1, icon: items.calendar, name: "Calendar", to: "/calendar" },
];

const styles = {
	bmBurgerButton: {
		position: "fixed",
		width: "36px",
		height: "30px",
		left: "36px",
		top: "36px",
	},
	bmBurgerBars: {
		background: "rgb(255, 171, 58)",
	},
	bmBurgerBarsHover: {
		background: "#a90000",
	},
	bmCrossButton: {
		height: "24px",
		width: "24px",
	},
	bmCross: {
		background: "#bdc3c7",
	},
	bmMenuWrap: {
		position: "fixed",
		height: "100%",
	},
	bmMenu: {
		background: "#315265",
		padding: "2.5em 1.5em 0",
		fontSize: "1.15em",
	},
	bmMorphShape: {
		fill: "#315265",
	},
	bmItemList: {
		color: "#b8b7ad",
		padding: "0.8em",
	},
	bmItem: {
		display: "inline-block",
		color: "rgb(255, 171, 58)",
		padding: "0.8em",
	},
	bmSpan: {
		marginLeft: 10,
		fontWeight: 700,
	},
};

const ButtonStyledAsLink = styled.button`
	background-color: transparent;
  	border: none;
  	cursor: pointer;
  	display: inline;
  	margin: 0;
  	padding: 0;
  	:hover, :focus {
  		text-decoration: underline;
  	}	
`;

const NameSpan = styled.span`
	 margin-left: 10px;
	 font-weight: 700;
`;

const DropdownMenuItem = ( item: DropdownMenuItemProps ) => {
	const [ visible, setVisible ] = useState( false );
	return (
		<div className="dropdown">
			<ButtonStyledAsLink
				style={{
					display: "inline-block",
					color: "rgb(255, 171, 58)",
					padding: "0.8em",
				}}
				onClick={() => setVisible( ! visible )}
			>
				<i className={item.icon}> </i>
				<NameSpan>{item.name}</NameSpan>
				<span className="caret"></span>
			</ButtonStyledAsLink>
			{visible &&
			<ul>
				{item.children.map( MenuItem )}
			</ul>
			}
		</div>
	);
};

const MenuItem = ( item: DropdownMenuItemProps ) => {
	const hasChildren = item.hasOwnProperty( "children" );
	return (
		hasChildren ?
			<DropdownMenuItem {...item} /> :
			<Link
				to={item.to}
				id={item.name}
				style={{
					display: "inline-block",
					color: "rgb(255, 171, 58)",
					padding: "0.8em",
				}}
			>
				<i className={item.icon}> </i>
				<NameSpan>{item.name}</NameSpan>
			</Link>
	);
};

const Menu = () => {
	return (
		<BurgerMenu styles={styles} disableAutoFocus>
			{menuItems.map( item => <MenuItem {...item} /> )}
		</BurgerMenu>
	);
};

export default Menu;

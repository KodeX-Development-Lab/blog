import { useApp } from "../ThemedApp";
import {
    Box,
    AppBar,
    Toolbar,
    Typography,
    IconButton,
} from "@mui/material";

import {
    Menu as MenuIcon,
    Add as AddIcon,
    Search as SearchIcon,
    LightMode as LightModeIcon,
    DarkMode as DarkModeIcon
} from "@mui/icons-material";
import { Link } from "react-router-dom";

export default function Header() {
    const { showSearchForm, setSearchShowForm, showForm, setShowForm, setShowDrawer, mode, setMode } = useApp();

    return (
        <AppBar position="static">
            <Toolbar>
                <IconButton
                    color="inherit"
                    edge="start"
                    onClick={() => setShowDrawer(true)}>
                    <MenuIcon />
                </IconButton>
                <Typography sx={{ flexGrow: 1, ml: 2 }} color="inherit">
                    <Link
                        href="/"
                        color="inherit"
                        underline="none"
                        >
                        KodeX
                    </Link>
                </Typography>
                <Box>
                    <IconButton
                        color="inherit"
                        onClick={() => { setSearchShowForm(!showSearchForm); setShowForm(false); }}>
                        <SearchIcon />
                    </IconButton>
                    <IconButton
                        color="inherit"
                        onClick={() => { setShowForm(!showForm); setSearchShowForm(false) }}>
                        <AddIcon />
                    </IconButton>
                    {mode === "dark" ? (
                        <IconButton
                            color="inherit"
                            edge="end"
                            onClick={() => setMode("light")}>
                            <LightModeIcon />
                        </IconButton>
                    ) : (
                        <IconButton
                            color="inherit"
                            edge="end"
                            onClick={() => setMode("dark")}>
                            <DarkModeIcon />
                        </IconButton>
                    )}
                </Box>
            </Toolbar>
        </AppBar>
    );
}
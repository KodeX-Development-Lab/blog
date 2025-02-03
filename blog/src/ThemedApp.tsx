import { createContext, useContext, useEffect, useMemo, useState } from "react";
import {
    CssBaseline,
    Snackbar,
    ThemeProvider,
    createTheme,
} from "@mui/material";
import { deepPurple, grey } from "@mui/material/colors";
import { createBrowserRouter, RouterProvider, useNavigate } from "react-router-dom";
import { QueryClientProvider, QueryClient } from "react-query";

import Template from "./Template";
import Home from "./pages/Home";
import Login from "./pages/Login";
import Register from "./pages/Register";
import Likes from "./pages/Likes";
import Profile from "./pages/Profile";
import Comments from "./pages/Comments";

import { fetchVerify } from "./libs/fetcher";
import PostDetail from "./pages/PostDetail";
import PostReactions from "./pages/PostReactions";
import CommentReactions from "./pages/CommentReactions";

// import App from "./App";
// import AppDrawer from "./components/AppDrawer";



export const AppContext = createContext<object>();

export function useApp() {
    return useContext(AppContext);
}

const router = createBrowserRouter([
    {
        path: "/",
        element: <Template />,
        children: [
            {
                path: "/",
                element: <Home />,
            },
            {
                path: "/login",
                element: <Login />,
            },
            {
                path: "/register",
                element: <Register />,
            },
            {
                path: "/posts/:id",
                element: <PostDetail />,
            },
            {
                path: "/posts/:id/reactions",
                element: <PostReactions />,
            },
            {
                path: "/posts/:id/comments/:commentId/reactions",
                element: <CommentReactions />,
            },
            {
                path: "/users/:id",
                element: <Profile />,
            },
        ],
    },
]);

export const queryClient = new QueryClient();

export default function ThemedApp() {
    const [showDrawer, setShowDrawer] = useState(false);
    const [showSearchForm, setSearchShowForm] = useState(false);
    const [showForm, setShowForm] = useState(false);
    const [globalMsg, setGlobalMsg] = useState(null);
    const [auth, setAuth] = useState(null);
    const [mode, setMode] = useState("dark");
    const [reactionTypes, setReactionTypes]  = useState(null);
    const [defaultReactionType, setDefaultReactionType]  = useState(null);

    useEffect(() => {
        fetchVerify().then(result => {
            if (result) setAuth(result?.data?.user);
        });
    }, []);

    const theme = useMemo(() => {
        return createTheme({
            palette: {
                mode,
                primary: deepPurple,
                banner: mode === "dark" ? grey[800] : grey[200],
                text: {
                    fade: grey[500],
                },

            },
        })
    }, [mode]);

    return (
        <ThemeProvider theme={theme}>
            <AppContext.Provider value={{
                showDrawer,
                setShowDrawer,
                showSearchForm,
                setSearchShowForm,
                showForm,
                setShowForm,
                globalMsg,
                setGlobalMsg,
                auth,
                setAuth,
                mode,
                setMode,
                reactionTypes,
                setReactionTypes,
                defaultReactionType,
                setDefaultReactionType,
            }}>
                <QueryClientProvider client={queryClient}>
                    <RouterProvider router={router} />
                </QueryClientProvider>
                <CssBaseline />
            </AppContext.Provider>
        </ThemeProvider>
    );
}
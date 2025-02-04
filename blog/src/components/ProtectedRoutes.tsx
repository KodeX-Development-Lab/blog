import { Navigate, Outlet } from "react-router-dom";
import { useApp } from "../ThemedApp";

export default function ProtectedRoutes() {
    const { auth } = useApp();

    return auth ? <Outlet /> : <Navigate to="/login" />
}
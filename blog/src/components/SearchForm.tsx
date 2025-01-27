import { useRef } from "react";
import {
    Box,
    TextField
} from "@mui/material";

export default function SearchForm({ add }: { add: (content: string) => void }) {
    const contentRef = useRef<HTMLInputElement>(null);

    return (
        <form>
            <Box sx={{ mb: 4, textAlign: "right" }}>
                <TextField
                    inputRef={contentRef}
                    type="text"
                    placeholder="Search"
                    fullWidth
                    multiline
                    sx={{ mb: 1 }}
                />
            </Box>
        </form>
    )
}
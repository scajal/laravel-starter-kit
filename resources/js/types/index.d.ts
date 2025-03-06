import { type Config } from 'ziggy-js';

export interface SharedProps {
    ziggy: Config & { location: string };
}
